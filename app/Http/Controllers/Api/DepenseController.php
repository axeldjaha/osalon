<?php

namespace App\Http\Controllers\Api;

use App\Depense;
use App\Http\Requests\DepenseRequest;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\DepenseSalonResource;
use App\Http\Resources\SalonResource;
use App\Http\Resources\ServiceResource;
use App\Salon;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DepenseController extends ApiController
{

    /**
     * Liste des dépenses selon mois
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $depenses = $salon->depenses()
                ->whereYear("date_depense", Carbon::now()->year)
                ->whereMonth("date_depense", $request->mois ?? Carbon::now()->month)
                ->orderByRaw("date_depense DESC, id DESC")
                ->get();

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "depenses" => DepenseResource::collection($depenses),
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show depenses for given salon
     *
     * @param Request $request
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Salon $salon)
    {
        /**
         * Si au moment de l'affichage, l'utilisateur a maintenant 1 seul salon,
         * renvoyer 204 pour retouner à IndexDepense et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json(new SalonResource(new Salon()), 204);
        }

        $depenses = $salon->depenses()
            ->whereYear("date_depense", Carbon::now()->year)
            ->whereMonth("date_depense", $request->mois ?? Carbon::now()->month)
            ->orderByRaw("date_depense DESC, id DESC")
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
            "date_depense" => $request->date ?? Carbon::now(),
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
            "date_depense" => $request->date,
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
