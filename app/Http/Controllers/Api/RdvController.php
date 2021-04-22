<?php

namespace App\Http\Controllers\Api;


use App\Client;
use App\Fakedata;
use App\Http\Requests\RdvRequest;
use App\Http\Resources\RdvResource;
use App\Http\Resources\SalonResource;
use App\Http\Resources\SmsResource;
use App\Jobs\BulkCustomSMS;
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
            return \response()->json(new Salon(), 204);
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
        $smsCounter = new SMSCounter();

        $now = Carbon::now();
        $createdAt = $now;
        $updatedAt = $now;
        $data = [];
        $smsArray = [];
        $volume = 0;
        foreach ($request->rdvs as $row)
        {
            $date = lcfirst($row["date_iso_format"]);
            $message = str_replace(["#Nom", "#Date", "#Heure"], [$row["client"], $date, $row["heure"]], trim($request->message));

            $smsInfo = $smsCounter->count($message);
            $volume += $smsInfo->messages * 1;

            $sms = new \stdClass();
            $sms->to = [$row["telephone"]];
            $sms->message = $message;
            $smsArray[] = $sms;

            $data[] = [
                "to" => $row["telephone"],
                "message" => $message,
                "date" => Carbon::now(),
                "user" => $this->user->name,
                "salon_id" => $this->salon->id,
                "created_at" => $createdAt,
                "updated_at" => $updatedAt,
            ];
        }

        if(count($smsArray) == 0)
        {
            return response()->json([
                "message" => "Aucun rendez-vous n'a été trouvé."
            ], 404);
        }

        if($volume <= $this->compte->sms_balance)
        {
            $this->compte->decrement("sms_balance", $volume);

            Queue::push(new BulkCustomSMS($smsArray, config("app.sms_client_sender")));

            $columns = [
                "to",
                "message",
                "date",
                "user",
                "salon_id",
                "created_at",
                "updated_at",
            ];
            $model = new Sms();
            batch()->insert($model, $columns, $data);
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
