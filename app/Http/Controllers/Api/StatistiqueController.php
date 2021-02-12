<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SalonResource;
use App\Http\Resources\LaveurResource;
use App\Http\Resources\PaiementResource;
use App\Http\Resources\RapportResource;
use App\Http\Resources\PrestationResource;
use App\Laveur;
use App\Paiement;
use App\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends ApiController
{

    public function encaissement(Request $request)
    {
        $date = Carbon::now();
        $results = $this->salon->paiements()
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth("created_at", $request->mois ??  $date->month)
            ->orderBy('created_at', "DESC")
            ->get();

        return response()->json(PaiementResource::collection($results));
    }

    /**
     * Liste recette de chaque mois
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recettes(Request $request)
    {
        $results = $this->salon->paiements()
            ->whereYear('created_at', Carbon::now()->year)
            ->select([DB::raw("MONTH(created_at) as mois"), DB::raw("(SUM(montant)) as recette")])
            ->orderBy('created_at', "DESC")
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get();

        return response()->json(PrestationResource::collection($results));
    }

    public function recettesParPressing(Request $request)
    {
        $recettesParPressing = [];
        $this->user->salons()->orderBy("nom")->each(function ($salon) use(&$recettesParPressing, $request)
        {
            $recette = $salon->paiements()
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $request->mois ?? Carbon::now()->month)
                ->select([DB::raw("(SUM(montant)) as recette")])
                ->first()->recette ?? 0;

            $recettesParPressing[] = [
                "mois" => $request->mois ?? Carbon::now()->month,
                "recette" => intval($recette),
                "salon" => new SalonResource($salon),
            ];
        });

        return response()->json($recettesParPressing);
    }

    public function rapport()
    {
        $date = Carbon::now();
        $salonsId = $this->user->salons()->pluck("id")->toArray();

        $today = Paiement::whereIn("salon_id", $salonsId)->whereDate("created_at", Carbon::today())->get();
        $thisMonth = Paiement::whereIn("salon_id", $salonsId)->whereYear("created_at", $date->year)->whereMonth("created_at", $date->month)->get();
        $lastMonth = Paiement::whereIn("salon_id", $salonsId)->whereYear("created_at", $date->year)->whereMonth("created_at", $date->subMonth())->get();

        return response()->json([
            "statistiqueRecette" => [
                "aujourdhui" => intval($today->sum("montant")),
                "mois_courant" => intval($thisMonth->sum("montant")),
                "mois_passe" => intval($lastMonth->sum("montant")),
            ],
            "statistiqueClient" => [
                "aujourdhui" => intval($today->count()),
                "mois_courant" => intval($thisMonth->count()),
                "mois_passe" => intval($lastMonth->count()),
            ],
        ]);
    }

    public function rapportDetaille()
    {
        return response()->json(RapportResource::collection($this->user->salons()->orderBy("nom")->get()));
    }

}
