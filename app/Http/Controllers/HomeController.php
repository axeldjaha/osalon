<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Paiement;
use App\Salon;
use App\Operateur;
use App\Prestation;
use App\Recette;
use App\Sms;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data["title"] = "Tableau de bord";
        $data["active"] = "dashboard";

        $data["moisCourant"] = Carbon::now()->locale('fr')->isoFormat('MMMM YYYY');
        $data["users"] = User::count();
        $data["salons"] = Salon::count();
        $transactions = Transaction::whereYear("date_transaction", date("Y"))
            ->whereMonth("date_transaction", date("m"))
            ->get();
        $data["transactions"] = $transactions->count();
        $data["montantTransactions"] = $transactions->sum("montant");
        $data["abonnementsExp"] = Abonnement::whereDate("echeance", "<", Carbon::now())->count();
        $data["recette"] = Recette::whereYear("created_at", date("Y"))
            ->whereMonth("created_at", date("m"))
            ->sum("montant");

        $data["sms"] = Sms::whereYear("created_at", date("Y"))
            ->whereMonth("created_at", date("m"))
            ->count();

        return view('home', $data);
    }
}
