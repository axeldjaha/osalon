<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Jobs\SendSMS;
use App\Salon;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use stdClass;

class AbonnementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "Abonnement";
        $data["active"] = "abonnement";

        $data["abonnements"] = Abonnement::with("salon")
            ->where("echeance", "<", Carbon::now())
            ->get();

        return view("abonnement.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["title"] = "Abonnement";
        $data["active"] = "abonnement";

        $data["salons"] = Salon::with("abonnement")->orderBy("nom")->get();
        $data["modes"] = collect([
            Transaction::$MODE_ESPECE => Transaction::$MODE_ESPECE,
            Transaction::$MODE_PAIEMENT_LIGNE => Transaction::$MODE_PAIEMENT_LIGNE,
            Transaction::$MODE_MOBILE_MONEY => Transaction::$MODE_MOBILE_MONEY,
        ]);

        return view("abonnement.create", $data);
    }

    public function reabonnement(Salon $salon)
    {
        $data["title"] = "Abonnement";
        $data["active"] = "abonnement";

        $data["salon"] = $salon;
        $data["modes"] = collect([
            Transaction::$MODE_ESPECE => Transaction::$MODE_ESPECE,
            Transaction::$MODE_MOBILE_MONEY => Transaction::$MODE_MOBILE_MONEY,
            Transaction::$MODE_PAIEMENT_LIGNE => Transaction::$MODE_PAIEMENT_LIGNE,
        ]);

        return view("abonnement.reabonnement", $data);
    }

    public function reabonner(Request $request, Salon $salon)
    {
        $this->validate($request, [
            "montant" => "required|numeric",
            "validite" => "required|numeric",
            "mode_paiement" => "required",
        ]);

        $reference = $salon->id . Carbon::now()->timestamp;
        $transaction = $salon->transactions()->where("statut", "!=", Transaction::$STATUT_TERMINE)->first();
        if($transaction != null)
        {
            $transaction->update([
                "reference" => $reference,
                "montant" => $request->montant,
                "validite" => $request->validite,
                "statut" => Transaction::$STATUT_TERMINE,
                "mode_paiement" => $request->mode_paiement,
                "date_transaction" => Carbon::now(),
                "salon_id" => $salon->id,
                "offre_id" => null,
            ]);
        }
        else
        {
            Transaction::create([
                "reference" => $reference,
                "montant" => $request->montant,
                "validite" => $request->validite,
                "statut" => Transaction::$STATUT_TERMINE,
                "mode_paiement" => $request->mode_paiement,
                "date_transaction" => Carbon::now(),
                "salon_id" => $salon->id,
                "offre_id" => null,
            ]);
        }

        $abonnement  = Carbon::parse($salon->abonnement->echeance);
        if($abonnement->greaterThanOrEqualTo(Carbon::now()))
        {
            $echeance = $abonnement->addDays($request->validite);
        }
        else
        {
            $echeance = Carbon::now()->addDays($request->validite);
        }

        $salon->abonnement->update([
            "echeance" => $echeance
        ]);

        $message =
            "Réabonnement effectué avec succès!".
            "\nVotre compte est actif jusqu'au " . $echeance->format("d/m/Y") . ".";
        $sms = new stdClass();
        $sms->to = [$salon->users()->where("role", User::$ROLE_SUPERVISEUR)->first()->telephone];
        $sms->message = $message;
        Queue::push(new SendSMS($sms));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Réabonnement effectué avec succès!');

        return redirect()->route("abonnement.index");
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
            "montant" => "required|numeric",
            "validite" => "required|numeric",
            "mode_paiement" => "required",
            "salon" => "required|exists:salons,id",
        ]);

        $salon = Salon::findOrFail($request->salon);

        $reference = $salon->id . Carbon::now()->timestamp;
        $transaction = $salon->transactions()->where("statut", "!=", Transaction::$STATUT_TERMINE)->first();
        if($transaction != null)
        {
            $transaction->update([
                "reference" => $reference,
                "montant" => $request->montant,
                "validite" => $request->validite,
                "statut" => Transaction::$STATUT_TERMINE,
                "mode_paiement" => $request->mode_paiement,
                "date_transaction" => Carbon::now(),
                "salon_id" => $salon->id,
                "offre_id" => null,
            ]);
        }
        else
        {
            Transaction::create([
                "reference" => $reference,
                "montant" => $request->montant,
                "validite" => $request->validite,
                "statut" => Transaction::$STATUT_TERMINE,
                "mode_paiement" => $request->mode_paiement,
                "date_transaction" => Carbon::now(),
                "salon_id" => $salon->id,
                "offre_id" => null,
            ]);
        }

        $abonnement  = Carbon::parse($salon->abonnement->echeance);
        if($abonnement->greaterThanOrEqualTo(Carbon::now()))
        {
            $echeance = $abonnement->addDays($request->validite);
        }
        else
        {
            $echeance = Carbon::now()->addDays($request->validite);
        }

        $salon->abonnement->update([
            "echeance" => $echeance
        ]);

        $message =
            "Réabonnement effectué avec succès!".
            "\nVotre compte est actif jusqu'au " . $echeance->format("d/m/Y") . ".";
        $sms = new stdClass();
        $sms->to = [$salon->users()->where("role", User::$ROLE_SUPERVISEUR)->first()->telephone];
        $sms->message = $message;
        Queue::push(new SendSMS($sms));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Réabonnement effectué avec succès!');

        return redirect()->route("abonnement.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("abonnement.index");
    }
}
