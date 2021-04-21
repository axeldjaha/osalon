<?php

namespace App\Http\Controllers\Api;

use App\Fakedata;
use App\Http\Requests\IndexPrestationRequest;
use App\Http\Requests\PanierRequest;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\PanierResource;
use App\Http\Resources\SalonResource;
use App\Panier;
use App\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PanierController extends ApiController
{
    /**
     * Liste des prestations selon date
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexPrestationRequest $request)
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $paniers = $salon->paniers()
                ->whereDate('date', $request->date ?? Carbon::today())
                ->orderBy("date", "desc")
                ->get();

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "paniers" => PanierResource::collection($paniers),
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show encaissements for given salon
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

        $depenses = $salon->paniers()
            ->whereDate('date', $request->date ?? Carbon::today())
            ->orderBy("date", "desc")
            ->get();

        return response()->json(PanierResource::collection($depenses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PanierRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PanierRequest $request)
    {
        $panier = Panier::create([
            "total" => $request->total,
            "date" => $request->date ?? Carbon::today(),
            "salon_id" => $this->salon->id,
        ]);

        foreach ($request->articles as $article)
        {
            $articleId = $article["article"]["id"];
            $quantite = $article["quantite"];
            $panier->articles()->sync([$articleId => ["quantite" => $quantite]], false);
        }

        return response()->json(new PanierResource(new Panier()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Panier $panier
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Panier $panier)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->paniers()->where("id", $panier->id)->delete())
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée."
            ], 404);
        }

        return response()->json(null, 204);
    }
}
