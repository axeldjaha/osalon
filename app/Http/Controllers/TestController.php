<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Admin;
use App\Client;
use App\FichierProspect;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\DepenseSalonResource;
use App\Http\Resources\PrestationResource;
use App\Jobs\SendSMS;
use App\Jobs\SMSDispatcher;
use App\Salon;
use App\Operateur;
use App\Service;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PDF;
use stdClass;

class TestController extends Controller
{
    private $user;
    private $salon;

    public function __construct()
    {
        //$this->middleware('auth');
        $this->user = User::where("email", "paxeldp@gmail.com")->first();
        $this->salon = $this->user->salons()->first();
    }

    public function test(Request $request)
    {
        //DB::table("users")->update(["password" => bcrypt("2909")]);
        $salon = Salon::first();
        //"total" => $totalDepense->total ?? 0,

        $query = "
            SELECT SUM(montant) AS total,
                   CASE
                   WHEN MONTH(date_depense) = 1 THEN 'Janvier' 
                   WHEN MONTH(date_depense) = 2 THEN 'Février' 
                   WHEN MONTH(date_depense) = 3 THEN 'Mars' 
                   WHEN MONTH(date_depense) = 4 THEN 'Avril' 
                   WHEN MONTH(date_depense) = 5 THEN 'Mai' 
                   WHEN MONTH(date_depense) = 6 THEN 'Juin' 
                   WHEN MONTH(date_depense) = 7 THEN 'Juillet' 
                   WHEN MONTH(date_depense) = 8 THEN 'Août' 
                   WHEN MONTH(date_depense) = 9 THEN 'Septembre' 
                   WHEN MONTH(date_depense) = 10 THEN 'Octobre' 
                   WHEN MONTH(date_depense) = 11 THEN 'Novembre' 
                   WHEN MONTH(date_depense) = 12 THEN 'Décembre'
                   ELSE NULL
               END AS mois
            FROM depenses
            INNER JOIN salons ON salons.id = depenses.salon_id
            INNER JOIN salon_user ON salon_user.salon_id = salons.id
            WHERE salon_user.user_id = ? AND
                  YEAR(date_depense) = ? AND
                  MONTH(date_depense) = ?
            GROUP BY mois";
        $totalDepense = DB::select($query, [$this->user->id, Carbon::now()->year, $request->mois ?? Carbon::now()->month])[0];

        if($this->user->salons()->count() > 1)
        {
            $query = "
            SELECT SUM(depenses.montant) AS depense,
                   salons.id AS salon_id
            FROM depenses
            INNER JOIN salons ON salons.id = depenses.salon_id
            INNER JOIN salon_user ON salon_user.salon_id = salons.id
            WHERE salon_user.user_id = ? AND
                  YEAR(date_depense) = ? AND
                  MONTH(date_depense) = ?
            GROUP BY salon_id
            ORDER BY depense DESC";
            $depenses = DB::select($query, [$this->user->id, Carbon::now()->year, $request->mois ?? Carbon::now()->month]);
            $depenses = collect($depenses);

            $depenses = [
                "mois" => $totalDepense->mois,
                "total" => $totalDepense->total,
                "depense_item" => null,
                "depense_salon" => DepenseSalonResource::collection($depenses),
            ];
        }
        else
        {
            $query = "
            SELECT depenses.id,
                   objet,
                   montant,
                   date_depense,
                   salons.id AS salon_id
            FROM depenses
            INNER JOIN salons ON salons.id = depenses.salon_id
            INNER JOIN salon_user ON salon_user.salon_id = salons.id
            WHERE salon_user.user_id = ? AND
                  YEAR(date_depense) = ? AND
                  MONTH(date_depense) = ?
            ORDER BY date_depense DESC";
            $depenses = DB::select($query, [$this->user->id, Carbon::now()->year, $request->mois ?? Carbon::now()->month]);
            $depenses = collect($depenses);

            $depenses = [
                "mois" => $totalDepense->mois,
                "total" => $totalDepense->total,
                "depense_item" => DepenseResource::collection($depenses),
                "depense_salon" => null,
            ];
        }

        return response()->json($depenses);

        return config("app.name");
    }

}
