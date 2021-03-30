<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Jobs\SendSMS;
use App\Salon;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use stdClass;

class AbonnementController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["title"] = "Abonnement";
        $data["active"] = "abonnement";

        $data["salons"] = Salon::orderBy("nom")->get();
        $data["modes"] = collect([
            Transaction::$MODE_ESPECE => Transaction::$MODE_ESPECE,
            Transaction::$MODE_PAIEMENT_LIGNE => Transaction::$MODE_PAIEMENT_LIGNE,
            Transaction::$MODE_MOBILE_MONEY => Transaction::$MODE_MOBILE_MONEY,
        ]);

        return view("salon.abonnement.create", $data);
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
            "salon" => "required|exists:salons,pid",
        ]);

        DB::transaction(function () use ($request)
        {
            $salon = Salon::where("pid", $request->salon)->firstOrFail();

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
                    "date" => Carbon::now(),
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
                    "date" => Carbon::now(),
                    "salon_id" => $salon->id,
                    "offre_id" => null,
                ]);
            }

            $dernierAbonnement  = Carbon::parse($salon->abonnements()->orderBy("id", "desc")->first()->echeance);
            if($dernierAbonnement->greaterThanOrEqualTo(Carbon::now()))
            {
                $echeance = $dernierAbonnement->addDays($request->validite);
            }
            else
            {
                $echeance = Carbon::now()->addDays($request->validite);
            }

            Abonnement::create([
                "date" => Carbon::today(),
                "echeance" => $echeance,
                "montant" => $request->montant,
                "salon_id" => $salon->id,
            ]);

            $message =
                "Votre réabonnement a été effectué avec succès!".
                "\nSalon: $salon->nom" .
                "\nActif jusqu'au: " . $echeance->format("d/m/Y");
            $sms = new stdClass();
            $sms->to = $salon->users()->pluck("telephone")->toArray();
            $sms->message = $message;
            Queue::push(new SendSMS($sms));

        }, 1);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Réabonnement effectué avec succès!');

        return redirect()->route("salon.index");
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

        return redirect()->route("salon.abonnement.index");
    }
}
