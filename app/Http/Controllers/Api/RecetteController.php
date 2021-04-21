<?php

namespace App\Http\Controllers\Api;

use App\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RecetteController extends ApiController
{
    public function index(Request $request)
    {
        $queryRecetteJournaliere = "
        SELECT (SUM(total)) as recette
        FROM paniers
        INNER JOIN salons ON salons.id = paniers.salon_id
        WHERE salons.id = ? AND
              DATE(paniers.date) = ?";

        $queryRecetteMois = "
        SELECT (SUM(total)) as recette,
               MONTH(paniers.date) AS index_mois,
               CASE
                   WHEN MONTH(paniers.date) = 1 THEN 'Janvier'
                   WHEN MONTH(paniers.date) = 2 THEN 'Février'
                   WHEN MONTH(paniers.date) = 3 THEN 'Mars'
                   WHEN MONTH(paniers.date) = 4 THEN 'Avril'
                   WHEN MONTH(paniers.date) = 5 THEN 'Mai'
                   WHEN MONTH(paniers.date) = 6 THEN 'Juin'
                   WHEN MONTH(paniers.date) = 7 THEN 'Juillet'
                   WHEN MONTH(paniers.date) = 8 THEN 'Août'
                   WHEN MONTH(paniers.date) = 9 THEN 'Septembre'
                   WHEN MONTH(paniers.date) = 10 THEN 'Octobre'
                   WHEN MONTH(paniers.date) = 11 THEN 'Novembre'
                   WHEN MONTH(paniers.date) = 12 THEN 'Décembre'
                   ELSE NULL
               END AS mois
        FROM paniers
        INNER JOIN salons ON salons.id = paniers.salon_id
        WHERE salons.id = ? AND
            YEAR(paniers.date) = ?
        GROUP BY index_mois, mois
        ORDER BY index_mois DESC";

        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "recette_journaliere" => DB::select($queryRecetteJournaliere, [$salon->id, Carbon::today()])[0]->recette,
                "recette_mois" => DB::select($queryRecetteMois, [$salon->id, Carbon::today()->year]),
            ];
        }

        return response()->json($salons);
    }

    public function show(Request $request, Salon $salon)
    {
        /**
         * Si au moment de l'affichage, l'utilisateur a maintenant 1 seul salon,
         * renvoyer 204 pour retouner à Index et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json([
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
            ], 204);
        }

        $queryRecetteJournaliere = "
        SELECT (SUM(total)) as recette
        FROM paniers
        INNER JOIN salons ON salons.id = paniers.salon_id
        WHERE salons.id = ? AND
              DATE(paniers.date) = ?";

        $queryRecetteMois = "
        SELECT (SUM(total)) as recette,
               MONTH(paniers.date) AS index_mois,
               CASE
                   WHEN MONTH(paniers.date) = 1 THEN 'Janvier'
                   WHEN MONTH(paniers.date) = 2 THEN 'Février'
                   WHEN MONTH(paniers.date) = 3 THEN 'Mars'
                   WHEN MONTH(paniers.date) = 4 THEN 'Avril'
                   WHEN MONTH(paniers.date) = 5 THEN 'Mai'
                   WHEN MONTH(paniers.date) = 6 THEN 'Juin'
                   WHEN MONTH(paniers.date) = 7 THEN 'Juillet'
                   WHEN MONTH(paniers.date) = 8 THEN 'Août'
                   WHEN MONTH(paniers.date) = 9 THEN 'Septembre'
                   WHEN MONTH(paniers.date) = 10 THEN 'Octobre'
                   WHEN MONTH(paniers.date) = 11 THEN 'Novembre'
                   WHEN MONTH(paniers.date) = 12 THEN 'Décembre'
                   ELSE NULL
               END AS mois
        FROM paniers
        INNER JOIN salons ON salons.id = paniers.salon_id
        WHERE salons.id = ? AND
            YEAR(paniers.date) = ?
        GROUP BY index_mois, mois
        ORDER BY index_mois DESC";

        return response()->json([
            "id" => $salon->id,
            "nom" => $salon->nom,
            "adresse" => $salon->adresse,
            "recette_journaliere" => DB::select($queryRecetteJournaliere, [$salon->id, Carbon::today()])[0]->recette,
            "recette_mois" => DB::select($queryRecetteMois, [$salon->id, Carbon::today()->year]),
        ]);
    }

}
