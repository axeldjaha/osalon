<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Compte;
use App\Jobs\SendSMS;
use App\Salon;
use App\Type;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data["title"] = "Comptes";
        $data["active"] = "compte";

        $data["comptes"] = Compte::orderBy("id", "desc")->get();

        return view("compte.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data["title"] = "Comptes";
        $data["active"] = "compte";

        $data["offres"] = Type::orderBy("montant")->get();

        return view("compte.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "salon" => "required",
            "adresse" => "required",
            "telephone" => "required|unique:users",
            "montant" => "required|numeric",
            "offre_id" => "required|exists:types:id",
        ]);

        $compte = Compte::create([]);

        $type = Type::find($request->offre_id);
        Abonnement::create([
            "montant" => $request->montant,
            "echeance" => Carbon::now()->addDays($type->validity),
            "type_id" => $type->id,
            "compte_id" => $compte->id,
        ]);

        $salon = Salon::create([
            "nom" => $request->salon,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "compte_id" => $compte->id,
        ]);

        $user = User::where("telephone", $request->telephone)->first();

        if($user == null)
        {
            $password = User::generatePassword($request->telephone);

            $user = User::create([
                "name" => $request->name,
                "telephone" => $request->telephone,
                "email" => $request->email,
                "compte_id" => null,
                "password" => bcrypt($password),
            ]);

            //Envoi du mot de passe par SMS
            $message =
                "Votre mot de passe est: $password" .
                "\nTéléchargez l'application " . config("app.name") . " sur playstore" .
                "\n" . config("app.playstore");
            $sms = new \stdClass();
            $sms->to = [$request->telephone];
            $sms->message = $message;
            Queue::push(new SendSMS($sms));
        }
        else // si le user existe
        {
            //Envoi d'une notification par SMS
            $message = "$salon->nom a été ajouté à votre compte " . config('app.name');
            $sms = new \stdClass();
            $sms->to = [$user->telephone];
            $sms->message = $message;
            Queue::push(new SendSMS($sms));
        }

        $user->salons()->sync([$salon->id], false);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Compte créé avec succès!');

        return redirect()->route("compte.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function show(Compte $compte)
    {
        $data["title"] = "Comptes";
        $data["active"] = "compte";

        $data["compte"] = $compte;

        return view("compte.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function edit(Compte $compte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compte $compte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Compte $compte
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Compte $compte)
    {
        $compte->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression avec succès!');

        return redirect()->route("compte.index");
    }
}
