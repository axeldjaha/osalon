<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SmsRequest;
use App\Http\Resources\SalonResource;
use App\Http\Resources\SmsResource;
use App\Jobs\BulkSMS;
use App\Message;
use App\Salon;
use App\Sms;
use App\SMSCounter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;

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
        $salon = null;
        $to = [];
        foreach ($request->to as $client)
        {
            $to[] = $client["telephone"];
            if($salon == null)
            {
                $salon = Salon::find($client["salon_id"]);
            }
        }
        $to = array_unique($to);

        if($salon == null || $this->compte->id != $salon->compte_id)
        {
            return response()->json([
                "message" => "Le salon n'existe pas ou a été supprimé"
            ], 404);
        }

        if(count($to) == 0)
        {
            return response()->json([
                "message" => "Aucun client n'a été sélectionné"
            ], 422);
        }

        $messageBody = trim($request->message);

        $smsCounter = new SMSCounter();
        $smsInfo = $smsCounter->count($messageBody);
        $volume = $smsInfo->messages * count($to);

        if($volume <= $this->compte->sms_balance)
        {
            $this->compte->decrement("sms_balance", $volume);

            $sms = Sms::create([
                "to" => count($request->to) . " client(s)",
                "message" => $messageBody,
                "user" => $this->user->name ?? $this->user->telephone,
                "salon_id" => $salon->id,
            ]);

            $message = new Message();
            $message->setBody($messageBody);
            $message->setTo($to);
            $message->setIndicatif($this->compte->pays->indicatif);
            $message->setSender(config("app.sms_sender_monsalon"));
            Queue::push(new BulkSMS($message));
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
     * @param Sms $sms
     * @return \Illuminate\Http\JsonResponse
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
