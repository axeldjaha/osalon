<?php

namespace App\Http\Controllers\Api;

use App\Fakedata;
use App\Http\Requests\SmsRequest;
use App\Http\Resources\SalonResource;
use App\Http\Resources\SmsResource;
use App\Jobs\BulkSMS;
use App\Lien;
use App\Salon;
use App\Sms;
use App\SMSCounter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SmsController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "sms" => SmsResource::collection($salon->sms()->orderBy("id", "desc")->get()),
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show prestations for given salon
     *
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( Salon $salon)
    {
        /**
         * Si au moment de l'affichage, l'utilisateur a maintenant 1 seul salon,
         * renvoyer 204 pour retouner à Index et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json(new SalonResource(new Salon()), 204);
        }

        return response()->json(SmsResource::collection($salon->sms()->orderBy("id", "desc")->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SmsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /*$data = json_encode($request->all());
        //Fakedata::create(["data" => $request->message]);
        Fakedata::create(["data" => $data]);
        return response()->json(["message" => "super!"], 400);*/

        $to = $this->salon->clients()
            ->whereIn("telephone", $request->to)
            ->pluck("telephone")
            ->toArray();
        $to = array_unique($to);

        if(count($to) == 0)
        {
            return response()->json([
                "message" => "Aucun client n'a été sélectionné."
            ], 422);
        }

        if($request->files->count())
        {
            /**
             * Création du lien
             */
            do
            {
                $url = Str::random(5);
            }
            while(Lien::where('url', $url)->count());
            $lien = Lien::create(['url' => $url, "sms_id" => null]);

            $url = config("app.url") . "/img/" . $lien->url;
            $message = trim($request->message) .
                "\nCliquez pour voir les photos" .
                "\n" . $url;
        }
        else
        {
            $message = trim($request->message);
        }

        $smsCounter = new SMSCounter();
        $smsInfo = $smsCounter->count($message);
        $volume = $smsInfo->messages * count($to);

        if($volume <= $this->compte->sms_balance)
        {
            $this->compte->decrement("sms_balance", $volume);

            $sms = Sms::create([
                "message" => $message,
                "recipient" => count($request->to),
                "date" => Carbon::now(),
                "user" => $this->user->name,
                "salon_id" => $this->salon->id,
            ]);

            if($request->files->count())
            {
                $destinationPath = public_path('/files/');

                if (!file_exists($destinationPath)) {
                    File::makeDirectory($destinationPath); //creates directory
                }

                $lien->update(["sms_id" => $sms->id]);

                /**
                 * Enregistrement des images
                 */
                foreach ($request->files as $file)
                {
                    $image = \App\Image::create([
                        "lien_id" => $lien->id,
                    ]);

                    $filename = md5($image->id) . '.' . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $filename);

                    $image->update(["nom" => $filename]);
                }
            }

            Queue::push(new BulkSMS($message, $to, config("app.sms_client_sender")));
        }
        else
        {
            if(isset($lien))
            {
                $lien->delete();
            }

            return response()->json([
                "message" => "Crédit SMS insuffisant. Veuillez recharger votre compte."
            ], 402);
        }

        return response()->json(new SmsResource(new Sms()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Sms $sms
     * @return Response
     */
    public function destroy($id)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->sms()->where("id", $id)->delete())
        {
            return response()->json([
                "message" => "Le SMS n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }

    /**
     * Destroy all sent SMS
     *
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll()
    {
        $this->salon->sms()->delete();

        return response()->json(null, 204);
    }

}
