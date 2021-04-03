<?php

namespace App\Http\Controllers\Api;

use App\Abonnement;
use App\Fakedata;
use App\Http\Requests\SalonRequest;
use App\Http\Resources\LavageDataResource;
use App\Http\Resources\SalonResource;
use App\Jobs\SendSMS;
use App\Offre;
use App\Salon;
use App\Service;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class SalonController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $salons = $this->user->salons()->orderBy("nom")->get();

        return response()->json(SalonResource::collection($salons));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SalonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SalonRequest $request)
    {
        $status = 200;

        DB::transaction(function () use (&$status, $request){
            $salon = Salon::create([
                "nom" => $request["nom"],
                "adresse" => $request["adresse"],
                "telephone" => $request["telephone"] ?? null,
            ]);

            $year = date("y");
            $month = date("m");
            $salon->update([
                "pid" => $year * 1000 + $month * 100 + $salon->id * 10 + $salon->id,
            ]);

            Abonnement::create([
                "date" => Carbon::now(),
                "montant" => Offre::first()->montant ?? null,
                "echeance" => Carbon::now()->addDays(Abonnement::$TRIAL),
                "salon_id" => $salon->id,
            ]);

            $this->user->salons()->sync([$salon->id], false);

            foreach ($request->users as $newUser)
            {
                $user = User::where("telephone", $newUser["telephone"])->first();

                //si user n'existe pas
                if($user == null)
                {
                    $password = User::generatePassword();

                    $user = User::create([
                        "name" => $newUser["name"],
                        "telephone" => $newUser["telephone"],
                        "email" => null,
                        "password" => bcrypt($password),
                    ]);

                    //Envoi du mot de passe par SMS
                    $message =
                        "Votre mot de passe est: $password" .
                        "\nTéléchargez l'application " . config("app.name") . " sur playstore.";
                    $sms = new \stdClass();
                    $sms->to = [$user->telephone];
                    $sms->message = $message;
                    Queue::push(new SendSMS($sms));

                    $status = 201;
                }
                else //si le user existe
                {
                    //Envoi d'une notification par SMS
                    $message =
                        "$salon a été rattaché à votre compte " . config('app.name') . "." .
                        "\nVous pouvez suivre les activités de ce salon à distance partout où vous etes.";
                    $sms = new \stdClass();
                    $sms->to = [$user->telephone];
                    $sms->message = $message;
                    Queue::push(new SendSMS($sms));

                    $status = 200;
                }

                $user->salons()->sync([$salon->id], false);
            }
        }, 1);

        return response()->json([
            "message" => "Votre salon a été créé. Le mot de passe de la gérante lui a été envoyé par SMS",
        ], $status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SalonRequest $request
     * @param \App\Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SalonRequest $request, Salon $salon): \Illuminate\Http\JsonResponse
    {
        if(!$this->user->salons()->where("id", $salon->id)->update([
            "nom" => $request->nom,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
        ]))
        {
            return response()->json([
                "message" => "Le salon n'existe pas ou a été supprimé"
            ], 404);
        }

        $salon = $salon->refresh();

        return response()->json(new SalonResource($salon));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Service $salon
     * @return Response
     */
    public function destroy(Salon $salon)
    {
        if($this->user->salons()->count() == 1)
        {
            return response()->json([
                "message" => "Vous ne pouvez pas supprimer le seul salon de votre compte."
            ], 404);
        }

        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->user->salons()->where("id", $salon->id)->exists())
        {
            return response()->json([
                "message" => "Le salon n'existe pas ou a été supprimé"
            ], 404);
        }

        foreach ($salon->users as $user)
        {
            if($user->salons()->count() == 1)
            {
                $user->delete();
            }
        }

        if(!$this->user->salons()->where("id", $salon->id)->delete())
        {
            return response()->json([
                "message" => "Le salon n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }

}
