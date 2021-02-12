<?php

namespace App\Http\Controllers\Api;

use App\Depense;
use App\Http\Requests\DepenseRequest;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\ServiceResource;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DepenseController extends ApiController
{
    /**
     * Somme des dépenses du mois en cours
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function depenseMensuelle()
    {
        $depenseMensuelle = $this->salon->depenses()
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum("montant");

        return response()->json([
            "id" => 0,
            "objet" => "Dépenses du mois de " . ucfirst(Carbon::now()->locale('fr')->isoFormat('MMMM')),
            "montant" => intval($depenseMensuelle),
            "date" => null,
            "mois" => Carbon::now()->month,
        ]);
    }

    /**
     * Liste des dépenses selon mois
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $depenses = $this->salon->depenses()
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', $request->mois ?? Carbon::now()->month)
            ->orderBy("date", "desc")
            ->select([
                //DB::raw("MONTH(date) as mois"), //renvoie le mois
                "id",
                "objet",
                DB::raw("(SUM(montant)) as montant"),
                "date",
                "salon_id",
            ])
            ->groupBy(DB::raw("MONTH(date)"),
                "id",
                "objet",
                "montant",
                "date",
                "salon_id")
            ->orderBy("id", "desc")
            ->get();
        return response()->json(DepenseResource::collection($depenses));
    }

    /**
     * Liste des dépenses groupées par mois
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupByMonth()
    {
        $depenses = $this->salon->depenses()
            ->whereYear('date', Carbon::now()->year)
            ->select([DB::raw("MONTH(date) as mois"), DB::raw("(SUM(montant)) as montant"),])
            ->orderBy('date', "DESC")
            ->groupBy(DB::raw("MONTH(date)"))
            ->get();
        return response()->json(DepenseResource::collection($depenses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepenseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepenseRequest $request)
    {
        $depense = Depense::create([
            "objet" => $request->objet,
            "montant" => $request->montant,
            "date" => $request->date ?? Carbon::now(),
            "salon_id" => $request->salon,
        ]);

        return response()->json(new DepenseResource($depense));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Depense $depense
     * @return Response
     */
    public function update(DepenseRequest $request, Depense $depense)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->depenses()->where("id", $depense->id)->update([
            "objet" => $request->objet,
            "montant" => $request->montant,
            "date" => $request->date,
        ]))
        {
            return response()->json([
                "message" => "La dépense n'existe pas ou a été supprimée"
            ], 404);
        }

        $depense = $depense->fresh();

        return response()->json(new DepenseResource($depense));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Depense $depense)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->depenses()->where("id", $depense->id)->delete())
        {
            return response()->json([
                "message" => "La dépense n'existe pas ou a été supprimée"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
