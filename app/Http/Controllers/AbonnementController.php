<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Compte;
use App\Jobs\SendSMS;
use App\Paiement;
use App\Salon;
use App\Transaction;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use stdClass;

class AbonnementController extends Controller
{

    /**
     * Create
     *
     * @param Compte $compte
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Compte $compte)
    {
        $data["title"] = "Comptes";
        $data["active"] = "compte";

        $data["compte"] = $compte;
        $data["currentAbonnement"] = $compte->abonnements()->orderBy("id", "desc")->first();
        $data["types"] = Type::orderBy("montant")->get();

        return view("abonnement.create", $data);
    }

    /**
     * Store
     *
     * @param Request $request
     * @param Compte $compte
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Compte $compte)
    {
        $this->validate($request, [
            "montant" => "required|numeric",
            "type_abonnement" => "required|exists:types,id",
        ]);

        $type = Type::find($request->type_abonnement);

        $currentEcheance = Carbon::parse($compte->abonnements()->orderBy("id", "desc")->first()->echeance);
        $echeance = $currentEcheance->lessThan(Carbon::now()) ?
            Carbon::now()->addDays($type->validity) : $currentEcheance->addDays($type->validity);

        Abonnement::create([
            "montant" => $request->montant,
            "echeance" => $echeance,
            "type_id" => $type->id,
            "compte_id" => $compte->id,
        ]);

        //$message = "Votre réabonnement a été effectué avec succès!";
        $message = "Votre réabonnement a été effectué avec succès!" .
            "\nLéquipe de " . config("app.name");
        $sms = new stdClass();
        $sms->to = [$compte->users()->first()->telephone];
        $sms->message = $message;
        Queue::push(new SendSMS($sms));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Réabonnement effectué avec succès!');

        return redirect()->route("compte.show", $compte);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Abonnement $abonnement
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Abonnement $abonnement)
    {
        if($abonnement->compte->abonnements()->count() == 1)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', 'Vous ne pouvez pas supprimer le seul abonnement de ce compte');
            return back();
        }

        $abonnement->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return back();
    }
}
