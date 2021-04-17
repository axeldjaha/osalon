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


        return view('home', $data);
    }
}
