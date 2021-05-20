<?php

namespace App\Http\Controllers;


use App\Compte;
use App\Salon;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        $data["total_compte"] = Compte::count();

        $query = "
        SELECT DISTINCT(compte_id)
        FROM abonnements
        WHERE DATE (echeance) >= ?";
        $data["total_compte_actif"] = count(DB::select($query, [Carbon::now()]));

        $data["total_salon"] = Salon::count();
        $data["total_user"] = User::count();
        $data["total_sms_balance"] = Compte::sum("sms_balance");

        return view('home', $data);
    }
}
