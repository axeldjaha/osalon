<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepotResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BilanController extends ApiController
{

    /**
     * Point du jour
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function point(Request $request)
    {
        $recetteQuery = "
                SELECT IFNULL(SUM(paniers.total), 0) AS total
                FROM paniers
                WHERE paniers.salon_id = ? AND DATE (paniers.date) = ?";

        $articleVenduQuery = "
                SELECT IFNULL(SUM(article_panier.quantite), 0) AS total
                FROM paniers
                INNER JOIN article_panier ON article_panier.panier_id = paniers.id
                WHERE paniers.salon_id = ? AND
                  DATE (paniers.date) = ? AND
                  article_panier.canceled = ?";

        $clientQuery = "
                SELECT COUNT(DISTINCT panier_id) AS total
                FROM paniers
                INNER JOIN article_panier ON article_panier.panier_id = paniers.id
                WHERE paniers.salon_id = ? AND
                  DATE (paniers.date) = ? AND
                  article_panier.canceled = ?";

        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $recetteResult = DB::select($recetteQuery, [
                $salon->id, $request->date ?? Carbon::today(),
            ]);

            $articleVenduResult = DB::select($articleVenduQuery, [
                $salon->id, $request->date ?? Carbon::today(), false
            ]);

            $clientResult = DB::select($clientQuery, [
                $salon->id, $request->date ?? Carbon::today(), false
            ]);

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "point" => [
                    "recette" => $recetteResult[0]->total,
                    "total_article_vendu" => $articleVenduResult[0]->total,
                    "total_client" => $clientResult[0]->total,
                ],
            ];
        }

        return response()->json($salons);
    }

    /**
     * Bilan mensuel
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bilan(Request $request)
    {
        $recetteQuery = "
                SELECT IFNULL(SUM(paniers.total), 0) AS total
                FROM paniers
                WHERE paniers.salon_id = ? AND
                  MONTH (paniers.date) = ? AND
                  YEAR (paniers.date) = ?";

        $depenseQuery = "
        SELECT IFNULL(SUM(montant), 0) AS depense
        FROM depenses
        WHERE depenses.salon_id = ? AND
              MONTH(depenses.date_depense) = ? AND
              YEAR(depenses.date_depense) = ?";

        $articleVenduQuery = "
                SELECT IFNULL(SUM(article_panier.quantite), 0) AS total
                FROM paniers
                INNER JOIN article_panier ON article_panier.panier_id = paniers.id
                WHERE paniers.salon_id = ? AND
                  MONTH (paniers.date) = ? AND
                  YEAR (paniers.date) = ? AND
                  article_panier.canceled = ?";

        $clientQuery = "
                SELECT COUNT(DISTINCT panier_id) AS total
                FROM paniers
                INNER JOIN article_panier ON article_panier.panier_id = paniers.id
                WHERE paniers.salon_id = ? AND
                  MONTH (paniers.date) = ? AND
                  YEAR (paniers.date) = ? AND
                  article_panier.canceled = ?";

        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $recetteResult = DB::select($recetteQuery, [
                $salon->id, $request->mois ?? Carbon::today()->month, Carbon::today()->year,
            ]);

            $depenseResult = DB::select($depenseQuery, [
                $salon->id, $request->mois ?? Carbon::today()->month, Carbon::today()->year,
            ]);

            $articleVenduResult = DB::select($articleVenduQuery, [
                $salon->id, $request->mois ?? Carbon::today()->month, Carbon::today()->year, false
            ]);

            $clientResult = DB::select($clientQuery, [
                $salon->id, $request->mois ?? Carbon::today()->month, Carbon::today()->year, false
            ]);

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "bilan" => [
                    "recette" => $recetteResult[0]->total,
                    "depense" => $depenseResult[0]->depense,
                    "solde" => $recetteResult[0]->total - $depenseResult[0]->depense,
                    "total_article_vendu" => $articleVenduResult[0]->total,
                    "total_client" => $clientResult[0]->total,
                ],
            ];
        }

        return response()->json($salons);
    }
}
