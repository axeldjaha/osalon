<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\SalonResource;
use App\Http\Resources\UserResource;
use App\Jobs\SendSMS;
use App\Salon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

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
        /**
         * Si au moment de l'affichage, l'utilisateur a maintenant 1 seul salon,
         * renvyer 204 pour retouner à Index et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json(new SalonResource(new Salon()), 204);
        }

        return response()->json(UserResource::collection($salon->users()->orderBy("name")->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::where("telephone", $request->telephone)->first();

        //si user n'existe pas
        if($user == null)
        {
            $password = User::generatePassword();

            $user = User::create([
                "name" => $request->name,
                "telephone" => $request->telephone,
                "email" => $request->email,
                "role" => $request->role,
                "password" => bcrypt($password),
            ]);

            $user->salons()->sync([$this->salon->id], false);

            //Envoi du mot de passe par SMS
            $message =
                "Votre mot de passe est: $password" .
                "\nCliquez sur le lien pour télécharger l'application " . config("app.name") .
                "\n" . config("app.playstore");
            $sms = new \stdClass();
            $sms->to = [$request->telephone];
            $sms->message = $message;
            Queue::push(new SendSMS($sms));

            $statusMessage = "L'utilisateur a été créé! Nous venons de lui envoyer son mot de passe par SMS";
            $status = 201;
        }
        else
        {
            //si user existe et que c'est un gérant
            if($user->role == User::$ROLE_GERANT)
            {
                $statusMessage = "Le numéro de téléphone saisi est déjà associé à un simple utilisateur existant";
                $status = 403;
            }
            //si le user est un superviseur
            elseif($user->role == User::$ROLE_SUPERVISEUR)
            {
                /*
                 * si on veut l'ajouter comme gérant ou superviseur dans un salon où il existe déjà
                 */
                if($user->salons()->where("id", $this->salon->id)->exists())
                {
                    $statusMessage = "Le numéro de téléphone saisi est déjà associé à un utilisateur de votre salon";
                    $status = 403;
                }
                //si le user n'existe pas dans ce salon
                else
                {
                    //si on veut l'ajouter comme gérant
                    if($request->role == User::$ROLE_GERANT)
                    {
                        $statusMessage = "Le numéro de téléphone saisi ne peut pas être associé à un simple utilisateur";
                        $status = 403;
                    }
                    else
                    {
                        $user->salons()->sync([$this->salon->id], false);

                        $salon = $this->salon->nom;
                        //Envoi d'une notification par SMS
                        $message =
                            "$salon a été rattaché à votre compte " . config('app.name') . "." .
                            "\nActualisez les données dans l'application pour suivre les encaissements de ce salon.";
                        $sms = new \stdClass();
                        $sms->to = [$request->telephone];
                        $sms->message = $message;
                        Queue::push(new SendSMS($sms));

                        $statusMessage = "L'utilisateur a été ajouté avec succès! Nous venons de lui envoyer une notification par SMS";
                        $status = 200;
                    }
                }
            }
        }

        return response()->json([
            "message" => $statusMessage,
        ], $status);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!DB::table("salon_user")->where(["salon_id" => $this->salon->id, "user_id" => $user->id])->exists())
        {
            return response()->json([
                "message" => "L'utilisateur n'existe pas ou a été supprimé"
            ], 404);
        }

        $statusMessage = "Compte utilisateur modifié avec succès!";
        $status = 200;

        if($user->role == User::$ROLE_GERANT)
        {
            $user->update([
                "role" => $request->role,
            ]);
        }
        elseif ($user->role == User::$ROLE_SUPERVISEUR)
        {
            //si le user a 1 seul compte
            if($user->salons()->count() == 1)
            {
                $user->update([
                    "role" => $request->role,
                ]);
            }
            //si le user a plusieurs salons
            else
            {
                //si on veut le basculer vers gérant
                if($request->role == User::$ROLE_GERANT)
                {
                    $statusMessage = "Cet utilisateur ne peut pas être un simple utilisateur";
                    $status = 403;
                }
            }
        }

        return response()->json([
            "message" => $statusMessage,
        ], $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
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
        }
        else
        {
            $user->salons()->detach($this->salon->id);
        }

        return response()->json(null, 204);
    }
}
