<?php

namespace App\Http\Controllers;

use App\Compte;
use App\Contact;
use App\Jobs\SendSMS;
use App\Message;
use App\Salon;
use App\SmsGroupe;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "Users";
        $data["active"] = "user";

        $query = "
        SELECT DISTINCT users.id,
               users.name,
               users.telephone,
               users.email,
               users.created_at,
               users.compte_id,
               t2.last_activity_at
        FROM users
        LEFT OUTER JOIN (
            SELECT userId,
                   MAX(created_at) AS last_activity_at
            FROM laravel_logger_activity
            GROUP BY userId
        ) AS t2 ON t2.userId = users.id
        ORDER BY t2.last_activity_at DESC";
        $users = DB::select($query);
        $data["users"] = $users;

        return view("user.index", $data);
    }

    public function create(Compte $compte)
    {
        $data["title"] = "Comptes";
        $data["active"] = "compte";

        $data["compte"] = $compte;

        return view("user.create", $data);
    }

    /**
     * Store user
     *
     * @param Request $request
     * @param Compte $compte
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Compte $compte)
    {
        $this->validate($request, [
            "name" => "nullable",
            "telephone" => "required",
            "email" => "nullable|unique:users",
            "salon" => "required|exists:salons,id",
        ]);

        $salon = Salon::find($request->salon);
        $user = User::where("telephone", $request->telephone)->first();

        // si user n'existe pas
        if($user == null)
        {
            $password = User::generatePassword($request->telephone);

            $user = User::create([
                "name" => $request->name,
                "telephone" => $request->telephone,
                "email" => $request->email,
                "compte_id" => $compte->id,
                "password" => bcrypt($password),
            ]);

            //Envoi du mot de passe par SMS
            $messageBody = "Votre mot de passe est: $password" .
                "\nTéléchargez l'application " . config("app.name") . " sur playstore" .
                "\n" . config("app.playstore");
            $to = [$request->telephone];

            $message = new Message();
            $message->setBody($messageBody);
            $message->setTo($to);
            $message->setIndicatif($compte->pays->indicatif);
            $message->setSender(config("app.sms_sender_osalon"));
            Queue::push(new SendSMS($message));

            session()->flash('type', 'alert-success');
            session()->flash('message', "L'utilisateur a été créé avec succès! Son mot de passe lui a été envoyé par SMS.");
        }
        // si user n'appartient pas au compte
        elseif ($user->compte->id != $salon->compte->id)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', 'Le numéro de téléphone est déjà associé à un autre compte');
            return back();
        }
        // si user déjà associé au salon
        elseif ($salon->users()->where("id", $user->id)->exists())
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', 'Le numéro de téléphone est déjà utilisé dans ce salon');
            return back();
        }
        else
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', "L'utilisateur a été ajouté au salon avec succès!");
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

        return redirect()->route("compte.show", $salon->compte->id);
    }

    public function show(User $user)
    {
        $data["title"] = "Users";
        $data["active"] = "user";
        $data["tab"] = "info";

        $data["user"] = $user;

        return view("user.show", $data);
    }

    public function resetPassword(User $user)
    {
        $user->update([
            "password" => bcrypt(User::generatePassword($user->telephone))
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Mot de passe réinitialisé avec succès!');

        return redirect()->route("user.index");
    }

    public function acces(User $user)
    {
        $data["title"] = "Users #$user->id :: Accès";
        $data["active"] = "User";
        $data["tab"] = "acces";

        $data["user"] = $user;

        return view("user.acces", $data);
    }

    public function lock(User $user)
    {
        $user->update([
            "activated" => false,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Utilisateur désactivé avec succès!');

        return back();
    }

    public function unlock(User $user)
    {
        $user->update([
            "activated" => true,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Utilisateur activé avec succès!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("user.index");
    }
}
