<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SalonResource;
use App\Http\Resources\PrestationResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BilanController extends ApiController
{
    public function getRecetteMois(Request $request)
    {

        $query = "
        SELECT (SUM(total)) as recette
        FROM prestations 
        INNER JOIN salons ON salons.id = prestations.salon_id
        INNER JOIN salon_user ON salon_user.salon_id = salons.id
        WHERE salon_user.user_id = ? AND
              DATE(prestations.created_at) = ?";
        $recetteJournaliere = DB::select($query, [$this->user->id, Carbon::today()])[0]->recette;

        $query = "
        SELECT (SUM(total)) as recette,
               MONTH(prestations.created_at) AS index_mois,
               CASE
                   WHEN MONTH(prestations.created_at) = 1 THEN 'Janvier' 
                   WHEN MONTH(prestations.created_at) = 2 THEN 'Février' 
                   WHEN MONTH(prestations.created_at) = 3 THEN 'Mars' 
                   WHEN MONTH(prestations.created_at) = 4 THEN 'Avril' 
                   WHEN MONTH(prestations.created_at) = 5 THEN 'Mai' 
                   WHEN MONTH(prestations.created_at) = 6 THEN 'Juin' 
                   WHEN MONTH(prestations.created_at) = 7 THEN 'Juillet' 
                   WHEN MONTH(prestations.created_at) = 8 THEN 'Août' 
                   WHEN MONTH(prestations.created_at) = 9 THEN 'Septembre' 
                   WHEN MONTH(prestations.created_at) = 10 THEN 'Octobre' 
                   WHEN MONTH(prestations.created_at) = 11 THEN 'Novembre' 
                   WHEN MONTH(prestations.created_at) = 12 THEN 'Décembre'
                   ELSE NULL
               END AS mois
        FROM prestations 
        INNER JOIN salons ON salons.id = prestations.salon_id
        INNER JOIN salon_user ON salon_user.salon_id = salons.id
        WHERE salon_user.user_id = ? AND
              YEAR(prestations.created_at) = ? 
        GROUP BY index_mois, mois
        ORDER BY index_mois DESC";
        $recetteMois = DB::select($query, [$this->user->id, Carbon::today()->year]);

        $bilan = [
            "nb_salon" => $this->user->salons()->count(),
            "recette_journaliere" => intval($recetteJournaliere),
            "recette_mois" => $recetteMois,
        ];

        return response()->json($bilan);
    }

    public function getRecetteSalons(Request $request)
    {

        $query = "
        SELECT (SUM(total)) as recette
        FROM prestations 
        INNER JOIN salons ON salons.id = prestations.salon_id
        INNER JOIN salon_user ON salon_user.salon_id = salons.id
        WHERE salon_user.user_id = ? AND
              DATE(prestations.created_at) = ?";
        $recetteJournaliere = DB::select($query, [$this->user->id, Carbon::today()])[0]->recette;

        $query = "
        SELECT (SUM(total)) as recette,
               salons.id,
               salons.nom AS salon
        FROM prestations 
        INNER JOIN salons ON salons.id = prestations.salon_id
        INNER JOIN salon_user ON salon_user.salon_id = salons.id
        WHERE salon_user.user_id = ? AND
              YEAR(prestations.created_at) = ? AND
              MONTH(prestations.created_at) = ?
        GROUP BY salons.id, salons.nom
        ORDER BY recette DESC";
        $recetteSalons = DB::select($query, [$this->user->id, Carbon::today()->year, $request->mois ?? 0]);

        $bilan = [
            "recette_journaliere" => intval($recetteJournaliere),
            "recette_salons" => $recetteSalons,
        ];

        return response()->json($bilan);
    }

}
