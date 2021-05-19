<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\Http\Requests\SalonRequest;
use App\Http\Resources\SalonResource;
use App\Jobs\SendSMS;
use App\Message;
use App\Salon;
use App\SmsGroupe;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class SalonController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
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
        $statusMessage = "Votre salon a été créé. Le mot de passe de la gérante lui a été envoyé par SMS";
        $status = 201;

        DB::transaction(function () use (&$status, &$statusMessage, $request)
        {
            $salon = Salon::create([
                "nom" => $request["nom"],
                "adresse" => $request["adresse"],
                "telephone" => $this->user->telephone,
                "compte_id" => $this->compte->id,
            ]);

            $this->user->salons()->sync([$salon->id], false);

            foreach ($request->users as $newUser)
            {
                $user = User::where("telephone", $newUser["telephone"])->first();

                //si user n'existe pas
                if($user == null)
                {
                    $password = User::generatePassword($newUser["telephone"]);

                    $user = User::create([
                        "name" => $newUser["name"],
                        "telephone" => $newUser["telephone"],
                        "email" => $request->email,
                        "compte_id" => $this->compte->id,
                        "password" => bcrypt($password),
                    ]);

                    //Envoi du mot de passe par SMS
                    $messageBody =
                        "Votre mot de passe est: $password" .
                        "\nTéléchargez l'application " . config("app.name") . " sur playstore\n" .
                        config("app.playstore");
                    $to = [$user->telephone];

                    $message = new Message();
                    $message->setBody($messageBody);
                    $message->setTo($to);
                    $message->setIndicatif($this->compte->pays->code);
                    $message->setSender(config("app.sms_sender_osalon"));
                    Queue::push(new SendSMS($message));
                }
                // si user n'appartient pas au compte
                elseif ($user->compte->id != $this->compte->id)
                {
                    $salon->delete();
                    $statusMessage = "Le numéro de telephone saisi est déjà associé à un autre compte";
                    $status = 422;
                    return;
                }
                else
                {
                    $statusMessage = "Votre salon a été créé avec succès!";
                    $status = 201;
                    return;
                }

                $user->salons()->sync([$salon->id], false);

                $smsGroup = SmsGroupe::where("intitule", SmsGroupe::$USERS)->first();
                if($smsGroup != null && !$smsGroup->contacts()->where("telephone", $user->telephone)->exists())
                {
                    Contact::create([
                        "nom" => $user->name,
                        "telephone" => $user->telephone,
                        "sms_groupe_id" => $smsGroup->id,
                    ]);
                }
            }
        }, 1);

        return response()->json([
            "message" => $statusMessage,
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
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
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

        $smsGroup = SmsGroupe::where("intitule", SmsGroupe::$USERS)->first();
        foreach ($salon->users as $user)
        {
            if($user->salons()->count() == 1)
            {
                $user->delete();
                if($smsGroup != null)
                {
                    $smsGroup->contacts()->where("telephone", $user->telephone)->delete();
                }
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
