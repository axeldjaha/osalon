<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\SalonResource;
use App\Http\Resources\ArticleResource;
use App\Salon;
use Illuminate\Http\Request;

class ArticleController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "articles" => ArticleResource::collection($salon->articles()->orderBy("libelle")->get()),
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show prestations for given salon
     *
     * @param Request $request
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Salon $salon)
    {
        /**
         * Si au moment de l'affichage, l'utilisateur a maintenant 1 seul salon,
         * renvoyer 204 pour retouner à Index et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json(new Salon(), 204);
        }

        return response()->json(ArticleResource::collection($salon->articles()->orderBy("libelle")->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ArticleRequest $request)
    {
        $service = Article::create([
            "libelle" => $request->libelle,
            "prix" => $request->prix,
            "stock" => $request->stock,
            "salon_id" => $this->salon->id,
        ]);

        return response()->json(new ArticleResource($service));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleRequest $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ArticleRequest $request, Article $article)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->articles()->where("id", $article->id)->update([
            "libelle" => $request->libelle,
            "prix" => $request->prix,
            "stock" => $request->stock
        ]))
        {
            return response()->json([
                "message" => "Le ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        $article = $article->fresh();

        return response()->json(new ArticleResource($article));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Article $article)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->articles()->where("id", $article->id)->delete())
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
