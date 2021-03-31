<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Jobs\SendSMS;
use App\Salon;
use App\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\View\View;
use stdClass;

class SalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data["title"] = "Salons";
        $data["active"] = "salon";

        $data["salons"] = Salon::orderBy("nom", 'asc')->get();
        $query = "
        SELECT DISTINCT(salon_id)
        FROM abonnements
        WHERE DATE (echeance) >= ?";
        $data["actifs"] = DB::select($query, [Carbon::now()]);

        return view("salon.index", $data);
    }

    /**
     * Display the specified resource.
     *
     * @param Salon $salon
     * @return Response
     */
    public function show(Salon $salon)
    {
        $data["title"] = "Salon";
        $data["active"] = "salon";

        $data["salon"] = $salon;

        return view("salon.show", $data);
    }

    /**
     * Réabonnement form
     *
     * @param Salon $salon
     * @return View
     */
    public function reabonnement(Salon  $salon)
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
     * Réabonner
     *
     * @param \Illuminate\Http\Request $request
     * @param Salon $salon
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reabonner(Request $request, Salon $salon)
    {
        $this->validate($request, [
            "montant" => "required|numeric",
            "validite" => "required|numeric",
            "mode_paiement" => "required",
            "salon" => "required|exists:salons,id",
        ]);

        DB::transaction(function () use ($request, $salon)
        {
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
     * Destroy abonnement
     *
     * @param Salon $salon
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function destroyAbonnement(Salon $salon, Abonnement  $abonnement)
    {
        if($salon->abonnements()->where("id", $abonnement->id)->delete())
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', 'Suppression effectuée avec succès!');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Salon $salon
     * @return Response
     * @throws Exception
     */
    public function destroy(Salon $salon)
    {
        $salon->users()->each(function ($user)
        {
            if($user->salons()->count() == 1)
            {
                $user->delete();
            }
        });

        $salon->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("salon.index");
    }
}
