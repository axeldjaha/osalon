<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Jobs\SendSMS;
use App\Salon;
use App\Transaction;
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
     * @param Salon $salon
     * @return \Illuminate\Http\Response
     */
    public function create(Salon  $salon)
    {
        $data["title"] = "Salon";
        $data["active"] = "salon";

        $data["salon"] = $salon;
        $data["modes"] = collect([
            Transaction::$MODE_ESPECE => Transaction::$MODE_ESPECE,
            //Transaction::$MODE_PAIEMENT_LIGNE => Transaction::$MODE_PAIEMENT_LIGNE,
            Transaction::$MODE_MOBILE_MONEY => Transaction::$MODE_MOBILE_MONEY,
        ]);

        return view("salon.abonnement.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Salon $salon
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Salon $salon)
    {
        $this->validate($request, [
            "montant" => "required|numeric|min:0",
            "validite" => "required|numeric|min:0",
            "mode_paiement" => "required",
            "salon" => "required|exists:salons,id",
        ]);

        DB::transaction(function () use ($request, $salon)
        {
            $dernierAbonnement  = Carbon::parse($salon->abonnements()->orderBy("id", "desc")->first()->echeance ?? Carbon::yesterday());
            if($dernierAbonnement->greaterThanOrEqualTo(Carbon::now()))
            {
                $echeance = $dernierAbonnement->addDays($request->validite);
            }
            else
            {
                $echeance = Carbon::now()->addDays($request->validite);
            }

            Abonnement::create([
                "montant" => $request->montant,
                "validite" => $request->validite,
                "echeance" => $echeance,
                "mode_paiement" => $request->mode_paiement,
                "salon_id" => $salon->id,
            ]);

            $message =
                "Votre réabonnement a été effectué avec succès!";
            $sms = new stdClass();
            $sms->to = $salon->users()->pluck("telephone")->toArray();
            $sms->message = $message;
            Queue::push(new SendSMS($sms));

        }, 1);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Réabonnement effectué avec succès!');

        return redirect()->route("salon.index");
    }

    public function edit(Salon  $salon, Abonnement $abonnement)
    {
        $data["title"] = "Salon";
        $data["active"] = "salon";

        $data["abonnement"] = $abonnement;
        $data["modes"] = collect([
            Transaction::$MODE_ESPECE => Transaction::$MODE_ESPECE,
            //Transaction::$MODE_PAIEMENT_LIGNE => Transaction::$MODE_PAIEMENT_LIGNE,
            Transaction::$MODE_MOBILE_MONEY => Transaction::$MODE_MOBILE_MONEY,
        ]);

        return view("salon.abonnement.edit", $data);
    }

    /**
     * Update
     *
     * @param Request $request
     * @param Salon $salon
     * @param Abonnement $abonnement
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Salon $salon, Abonnement $abonnement)
    {
        $this->validate($request, [
            "montant" => "required|numeric|min:0",
            "validite" => "required|numeric|min:0",
            "mode_paiement" => "required",
            "salon" => "required|exists:salons,id",
        ]);

        $abonnement->delete();

        $dernierAbonnement  = Carbon::parse($salon->abonnements()->orderBy("id", "desc")->first()->echeance ?? Carbon::yesterday());
        if($dernierAbonnement->greaterThanOrEqualTo(Carbon::now()))
        {
            $echeance = $dernierAbonnement->addDays($request->validite);
        }
        else
        {
            $echeance = Carbon::now()->addDays($request->validite);
        }

        Abonnement::create([
            "montant" => $request->montant,
            "validite" => $request->validite,
            "echeance" => $echeance,
            "mode_paiement" => $request->mode_paiement,
            "salon_id" => $salon->id,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Modification effectuée avec succès!');

        return redirect()->route("salon.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Salon $salon
     * @param Abonnement $abonnement
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Salon $salon, Abonnement $abonnement)
    {
        $abonnement->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return back();
    }
}
