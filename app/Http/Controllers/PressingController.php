<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Client;
use App\Jobs\SendSMS;
use App\Jobs\SMSDispatcher;
use App\Salon;
use App\Jobs\MailJob;
use App\Mail\NewAccount;
use App\Mail\UserAccess;
use App\Operateur;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use stdClass;

class PressingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "Pressing";
        $data["active"] = "salon";

        $data["salons"] = Salon::orderBy("id", 'desc')->get();

        return view("salon.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["title"] = "Nouveau salon";
        $data["active"] = "salon";

        $data["clients"] = Client::orderBy("id", "desc")->get();

        return view("salon.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "nom" => "required",
            "adresse" => "required",
            "telephone" => "required|min:8|max:8",
        ]);

        $password = User::generatePassword();

        $user = User::create([
            "name" => null,
            "telephone" => $request->telephone,
            "role" => User::$ROLE_SUPERVISEUR,
            "password" => bcrypt($password),
        ]);

        $salon = Salon::create([
            "nom" => $request->nom,
            "adresse" => $request->adresse,
        ]);

        $user->salons()->sync([$salon->id], false);

        $pid = date("y") . date("m") . $salon->id;
        Abonnement::create([
            "pid" => $pid,
            "echeance" => Carbon::now()->addDays(10),
            "salon_id" => $salon->id,
        ]);

        // Envoi du mot de passe par SMS
        $to = [$request->telephone];
        $message = "Votre mot de passe est: $password";
        $sms = new stdClass();
        $sms->to = $to;
        $sms->message = $message;
        Queue::push(new SendSMS($sms));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Pressing créé avec succès!');

        return redirect()->route('salon.show', $salon);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salon  $salon
     * @return \Illuminate\Http\Response
     */
    public function show(Salon $salon)
    {
        $data["title"] = "Pressing #$salon->id";
        $data["active"] = "salon";
        $data["tab"] = "info";

        $data["salon"] = $salon;
        $data["userCount"] = $salon->users()->where("role", User::$ROLE_GERANT)->count();

        return view("salon.show", $data);
    }

    public function users(Salon $salon)
    {
        $data["title"] = "Pressing #$salon->id";
        $data["active"] = "salon";
        $data["tab"] = "users";

        $data["salon"] = $salon;
        $data["users"] = $salon->users()->orderBy("id", "desc")->get();

        return view("salon.users.index", $data);
    }

    public function createUser(Salon $salon)
    {
        $data["title"] = "Pressing #$salon->id";
        $data["active"] = "salon";
        $data["tab"] = "users";

        $data["salon"] = $salon;
        $data["roles"] = collect([
            User::$ROLE_SUPERVISEUR => "Superviseur",
            User::$ROLE_GERANT => "Gérant",
        ]);

        return view("salon.users.create", $data);
    }

    public function storeUser(Salon $salon, Request $request)
    {
        $this->validate($request, [
            "nom" => "nullable",
            "role" => "required",
            "telephone" => "required",
        ]);

        $user = User::where("telephone", $request->telephone)->first();

        //si user n'existe pas
        if($user == null)
        {
            $password = User::generatePassword();

            $user = User::create([
                "name" => $request->nom,
                "telephone" => $request->telephone,
                "email" => $request->email,
                "role" => User::$ROLE_SUPERVISEUR,
                "password" => bcrypt($password),
            ]);

            $user->salons()->sync([$salon->id], false);

            //Envoi du mot de passe par SMS
            $message =
                "Votre mot de passe est: $password" .
                "\nCliquez sur le lien pour télécharger l'application " . env("APP_NAME") .
                "\n" . env("PLAYSTORE_LINK");
            Queue::push(new SMSDispatcher([$request->telephone], $message));

            $alerteMessage = "L'utilisateur a été créé avec succès! Son mot de passe vient de lui être envoyé par SMS";
            $alertType = "alert-success";
        }
        else
        {
            //si user existe et que c'est un gérant
            if($user->role == User::$ROLE_GERANT)
            {
                $alerteMessage = "Cet utilisateur est déjà enregistré comme gérant";
                $alertType = "alert-danger";
            }
            //si le user est un superviseur
            elseif($user->role == User::$ROLE_SUPERVISEUR)
            {
                /*
                 * si on veut l'ajouter comme gérant ou superviseur dans un salon où il existe déjà
                 */
                if($user->salons()->where("id", $salon->id)->exists())
                {
                    $alerteMessage = "Cet utilisateur est déjà enregistré comme superviseur";
                    $alertType = "alert-danger";
                }
                //si le user n'existe pas dans ce salon
                else
                {
                    //si on veut l'ajouter comme gérant
                    if($request->role == User::$ROLE_GERANT)
                    {
                        $alerteMessage = "Cet utilisateur ne peut être un gérant";
                        $alertType = "alert-danger";
                    }
                    else
                    {
                        $user->salons()->sync([$salon->id], false);

                        $salonAuto = $salon->nom;
                        //Envoi d'une notification par SMS
                        $message =
                            "$salonAuto a été rattaché à votre compte " . env("APP_NAME") . "." .
                            "\nVous pouvez suivre toutes les opérations de ce salon auto partout où vous etes!";
                        Queue::push(new SMSDispatcher([$request->telephone], $message));

                        $alerteMessage = "L'utilisateur a été ajouté avec succès! Une notification vient de lui être envyée par SMS";
                        $alertType = "alert-success";
                    }
                }
            }
        }

        session()->flash('type', $alertType);
        session()->flash('message', $alerteMessage);

        return redirect()->route("salon.users", $salon);
    }

    public function destroyeUser(Request $request)
    {
        User::destroy($request->user);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Compte utilisateur supprimé avec succès!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Salon $salon
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Salon $salon)
    {
        $salon->users()->each(function ($user)
        {
            if($user->salons()->count() == 1)
            {
                User::destroy($user->id);
            }
        });

        $salon->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("salon.index");
    }
}
