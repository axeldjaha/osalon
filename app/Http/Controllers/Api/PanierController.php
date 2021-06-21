<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Fakedata;
use App\Http\Requests\IndexPrestationRequest;
use App\Http\Requests\PanierRequest;
use App\Http\Resources\PanierResource;
use App\Http\Resources\SalonResource;
use App\Item;
use App\Panier;
use App\Salon;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
                ->orderBy("id", "desc")
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
            return \response()->json(new Salon(), 204);
        }

        $paniers = $salon->paniers()
            ->whereDate('date', $request->date ?? Carbon::today())
            ->orderBy("id", "desc")
            ->get();

        return response()->json(PanierResource::collection($paniers));
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
            "date" => $request->date != null ? $request->date . " " . date("H:i")  : Carbon::today(),
            "salon_id" => $this->salon->id,
        ]);

        $date = Carbon::now();
        $createdAt = $date;
        $updatedAt = $date;
        $items = [];
        foreach ($request->json()->get("items") ?? [] as $item)
        {
            $items[] = [
                "nom" => $item["nom"],
                "prix_unitaire" => $item["prix_unitaire"],
                "quantite" => $item["quantite"],
                "date" => $panier->date,
                "panier_id" => $panier->id,
                "salon_id" => $this->salon->id,
                "created_at" => $createdAt,
                "updated_at" => $updatedAt,
            ];
        }

        if(count($items) > 0)
        {
            $model = new Item();
            $columns = [
                "nom",
                "prix_unitaire",
                "quantite",
                "date",
                "panier_id",
                "salon_id",
                "created_at",
                "updated_at",
            ];
            batch()->insert($model, $columns, $items);
        }

        return response()->json(new PanierResource(new Panier()));
    }

    /**
     * Cancel
     *
     * @param Request $request
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelItem(Request $request, Item $item)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if($this->salon->id != $item->salon->id)
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        $item->update(["canceled" => true]);

        return response()->json(null, 204);
    }

    /**
     * Delete article from panier
     *
     * @param Request $request
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItem(Request $request, Item $item)
    {
        $panier = $item->paniers;

        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->items()->where("id", $item->id)->delete())
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        if($panier->items()->count() == 0)
        {
            $panier->delete();
        }

        return response()->json(null, 204);
    }

    public function destroy(Panier $panier)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->paniers()->where("id", $panier->id)->delete())
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
