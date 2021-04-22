<?php

namespace App\Http\Controllers\Api;


use App\Client;
use App\Fakedata;
use App\Http\Requests\RdvRequest;
use App\Http\Resources\RdvResource;
use App\Http\Resources\SalonResource;
use App\Http\Resources\SmsResource;
use App\Jobs\BulkSMS;
use App\Rdv;
use App\Salon;
use App\Sms;
use App\SMSCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class RdvController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = "
        SELECT date,
          COUNT(*) AS total
        FROM rdvs
        WHERE salon_id = ? AND date >= ?
        GROUP BY date
        ORDER BY date";
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $rdvData = [];
            $rdvs = DB::select($query, [$salon->id, Carbon::today()]);
            foreach ($rdvs as $rdv)
            {
                $rdvData[] = [
                    "date" => $rdv->date,
                    "date_iso_format" => ucfirst(Carbon::parse($rdv->date)->locale("fr_FR")->isoFormat('dddd DD MMMM')),
                    "total" => $rdv->total,
                    "salon_id" => $salon->id,
                ];
            }

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "telephone" => $salon->telephone,
                "rdvs" => $rdvData,
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show prestations for given salon
     *
     * @param Request $request
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Salon $salon)
    {
        /**
         * Si au moment de l'affichage, l'utilisateur a maintenant 1 seul salon,
         * renvoyer 204 pour retouner à Index et auto reactualiser
         */
        if($request->date == null && $this->user->salons()->count() == 1)
        {
            return \response()->json(new SalonResource(new Salon()), 204);
        }

        if($request->date != null)
        {
            $rdvs = $salon->rdvs()
                ->where("date", "=", $request->date)
                ->orderBy("heure")
                ->get();
        }
        else
        {
            $query = "
            SELECT date,
              COUNT(*) AS total
            FROM rdvs
            WHERE salon_id = ? AND date >= ?
            GROUP BY date
            ORDER BY date";
            $rdvData = [];
            $rdvs = DB::select($query, [$salon->id, Carbon::today()]);
            foreach ($rdvs as $rdv)
            {
                $rdvData[] = [
                    "date" => $rdv->date,
                    "date_iso_format" => ucfirst(Carbon::parse($rdv->date)->locale("fr_FR")->isoFormat('dddd DD MMMM')),
                    "total" => $rdv->total,
                    "salon_id" => $salon->id,
                ];
            }

            return response()->json($rdvData);
        }

        return response()->json(RdvResource::collection($rdvs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RdvRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RdvRequest $request)
    {
        $rdv = Rdv::create([
            "date" => $request->date,
            "heure" => $request->heure,
            "client" => $request->client,
            "telephone" => $request->telephone,
            "salon_id" => $request->salon,
        ]);

        return response()->json(new RdvResource($rdv));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RdvRequest $request
     * @param Rdv $rdv
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RdvRequest $request, Rdv $rdv)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->rdvs()->where("id", $rdv->id)->update([
            "date" => $request->date,
            "heure" => $request->heure,
            "client" => $request->client,
            "telephone" => $request->telephone,
        ]))
        {
            return response()->json([
                "message" => "Le RDV n'existe pas ou a été supprimé"
            ], 404);
        }

        $rdv = $rdv->fresh();

        return response()->json(new RdvResource($rdv));
    }

    /**
     * Rappeler les RDV aux clientes par SMS
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rappelerRDV(Request $request)
    {
        $to = $this->salon->rdvs()
            ->whereIn("id", $request->to)
            ->pluck("telephone")
            ->toArray();
        $to = array_unique($to);

        if(count($to) == 0)
        {
            return response()->json([
                "message" => "Aucun client n'a été sélectionné."
            ], 422);
        }

        $message = str_replace("\r\n", "\n", trim($request->message));
        $smsCounter = new SMSCounter();
        $smsInfo = $smsCounter->count($message);
        $volume = $smsInfo->messages * count($to);

        if($volume <= $this->compte->sms_balance)
        {
            $this->compte->decrement("sms_balance", $volume);

            $newClients = [];
            $now = Carbon::now();
            $createdAt = $now;
            $updatedAt = $now;

            foreach ($request->to as $id)
            {
                $rdv = Rdv::find($id);
                $date = ucfirst(Carbon::parse($rdv->date)->locale("fr_FR")->isoFormat('dddd DD MMMM'));
                $message = str_replace(["#Nom", "#Date", "#Heure"], [$rdv->client, $date, date("H:i", strtotime($rdv->heure))], $request->message);
                Sms::create([
                    "message" => $message,
                    "recipient" => 1,
                    "date" => Carbon::now(),
                    "user" => $this->user->name,
                    "salon_id" => $this->salon->id,
                ]);

                if(!$this->salon->clients()->where("telephone", $rdv->telephone)->exists())
                {
                    $newClients[] = [
                        $rdv->client,
                        $rdv->telephone,
                        $this->salon->id,
                        $createdAt,
                        $updatedAt,
                    ];
                }
                else
                {
                    Fakedata::create(["data" => $rdv->telephone]);
                }

                Queue::push(new BulkSMS($message, [$rdv->telephone], config("app.sms_client_sender")));
            }

            if(count($newClients) > 0)
            {
                $columns = [
                    "client",
                    "telephone",
                    "salon_id",
                    "created_at",
                    "updated_at",
                ];
                $model = new Client();
                batch()->insert($model, $columns, $newClients);
            }

        }
        else
        {
            return response()->json([
                "message" => "Crédit SMS insuffisant. Veuillez recharger votre compte."
            ], 402);
        }

        return response()->json(new SmsResource(new Sms()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rdv $rdv
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Rdv $rdv)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->rdvs()->where("id", $rdv->id)->delete())
        {
            return response()->json([
                "message" => "Le RDV n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
