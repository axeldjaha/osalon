<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\SalonResource;
use App\Http\Resources\UserResource;
use App\Jobs\SendSMS;
use App\Message;
use App\Salon;
use App\SmsGroupe;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Response;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "users" => UserResource::collection($salon->users()->orderBy("name")->get()),
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show users for given salon
     *
     * @param \Illuminate\Http\Request $request
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Salon $salon)
    {
        return response()->json(UserResource::collection($salon->users()->orderBy("name")->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request)
    {
        $statusMessage = "L'utilisateur a été créé! Nous venons de lui envoyer son mot de passe par SMS";
        $status = 201;

        DB::transaction(function () use (&$status, &$statusMessage, $request)
        {
            $user = User::where("telephone", $request->telephone)->first();

            //si user n'existe pas
            if($user == null)
            {
                $password = User::generatePassword($request->telephone);

                $user = User::create([
                    "name" => $request->name,
                    "telephone" => $request->telephone,
                    "email" => $request->email,
                    "compte_id" => $this->compte->id,
                    "password" => bcrypt($password),
                ]);

                //Envoi du mot de passe par SMS
                $messageBody =
                    "Votre mot de passe est: $password" .
                    "\nTéléchargez l'application " . config("app.name") . " sur playstore\n" .
                    config("app.playstore");
                $to = [$request->telephone];

                $message = new Message();
                $message->setBody($messageBody);
                $message->setTo($to);
                $message->setIndicatif($this->compte->pays->indicatif);
                $message->setSender(config("app.sms_sender_osalon"));
                Queue::push(new SendSMS($message));
            }
            // si user n'appartient pas au compte
            elseif ($user->compte->id != $this->compte->id)
            {
                $statusMessage = "Le numéro de telephone saisi est déjà associé à un autre compte";
                $status = 422;
                return;
            }
            // si user déjà associé au salon
            elseif ($this->salon->users()->where("id", $user->id)->exists())
            {
                $statusMessage = "Le numéro de telephone saisi est déjà utilisé dans ce salon";
                $status = 422;
                return;
            }
            else
            {
                $statusMessage = "L'utilisateur a été ajouté à votre salon";
            }

            $user->salons()->sync([$this->salon->id], false);

            $smsGroup = SmsGroupe::where("intitule", SmsGroupe::$USERS)->first();
            if($smsGroup != null && !$smsGroup->contacts()->where("telephone", $user->telephone)->exists())
            {
                Contact::create([
                    "nom" => $user->name,
                    "telephone" => $user->telephone,
                    "sms_groupe_id" => $smsGroup->id,
                ]);
            }

        }, 1);

        return response()->json([
            "message" => $statusMessage,
        ], $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if($this->user->id == $user->id)
        {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à effectuer cette action"
            ], 401);
        }

        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!DB::table("salon_user")->where(["salon_id" => $this->salon->id, "user_id" => $user->id])->exists())
        {
            return response()->json([
                "message" => "L'utilisateur n'existe pas ou a été supprimé"
            ], 404);
        }

        if($user->salons()->count() == 1)
        {
            User::destroy($user->id);
            $smsGroup = SmsGroupe::where("intitule", SmsGroupe::$USERS)->first();
            if($smsGroup != null)
            {
                $smsGroup->contacts()->where("telephone", $user->telephone)->delete();
            }
        }
        else
        {
            $user->salons()->detach($this->salon->id);
        }

        return response()->json(null, 204);
    }
}
