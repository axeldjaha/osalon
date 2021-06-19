<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Fakedata;
use App\Http\Requests\IndexPrestationRequest;
use App\Http\Requests\PanierRequest;
use App\Http\Resources\PanierResource;
use App\Http\Resources\SalonResource;
use App\Panier;
use App\Salon;
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

        $depenses = $salon->paniers()
            ->whereDate('date', $request->date ?? Carbon::today())
            ->orderBy("id", "desc")
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
            "date" => $request->date != null ? $request->date . " " . date("H:i")  : Carbon::today(),
            "salon_id" => $this->salon->id,
        ]);

        foreach ($request->article_paniers as $article_panier)
        {
            $article_id = $article_panier["article"]["id"];
            $quantite = $article_panier["quantite"];
            $panier->articles()->sync([$article_id => ["quantite" => $quantite]], false);
        }

        return response()->json(new PanierResource(new Panier()));
    }

    /**
     * Cancel
     *
     * @param Request $request
     * @param Panier $panier
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function cancelArticle(Request $request, Panier $panier, Article $article)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if($this->salon->id != $panier->salon->id || $this->salon->id != $article->salon->id)
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        DB::table("article_panier")->where([
            "article_id" => $article->id,
            "panier_id" => $panier->id,
        ])->update(["canceled" => true]);

        return response()->json(null, 204);
    }

    /**
     * Delete article from panier
     *
     * @param Request $request
     * @param Panier $panier
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteArticle(Request $request, Panier $panier, Article $article)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        $row = [
            "article_id" => $article->id,
            "panier_id" => $panier->id,
        ];
        if($this->salon->id != $panier->salon->id || !DB::table("article_panier")->where($row)->delete())
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        if($panier->articles()->count() == 0)
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
