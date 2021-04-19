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
        $data["offres"] = Type::orderBy("montant")->get();

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
            "offre_id" => "required|exists:types:id",
        ]);

        $type = Type::find($request->offre_id);

        $echeance = Carbon::parse($compte->abonnements()->orderBy("id", "desc")->first()->echeance);
        $echeance = $echeance->lessThan(Carbon::now()) ?
            Carbon::now()->addDays($type->validity) : $echeance->addDays($type->validity);

        Abonnement::create([
            "montant" => $request->montant,
            "echeance" => $echeance,
            "type_id" => $type->id,
            "compte_id" => $compte->id,
        ]);

        $message = "Votre réabonnement a été effectué avec succès!";
        $sms = new stdClass();
        $sms->to = $compte->users()->first("telephone")->toArray();
        $sms->message = $message;
        Queue::push(new SendSMS($sms));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Réabonnement effectué avec succès!');

        return redirect()->route("compte.index");
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
        $abonnement->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return back();
    }
}
