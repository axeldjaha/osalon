<?php

namespace App\Http\Controllers\Api;


use App\Client;
use App\Http\Requests\RdvRequest;
use App\Http\Resources\RdvResource;
use App\Rdv;
use App\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RdvController extends ApiController
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
            $rdvs = $salon->rdvs()
                ->where("date", ">=", Carbon::today())
                ->orderBy("date")
                ->get();

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "telephone" => $salon->telephone,
                "rdvs" => RdvResource::collection($rdvs),
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
         * renvoyer 204 pour retouner à IndexDepense et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json(new Salon(), 204);
        }

        $rdvs = $salon->rdvs()
            ->where("date", ">=", Carbon::today())
            ->orderBy("date")
            ->get();

        return response()->json(RdvResource::collection($rdvs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RdvRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RdvRequest $request)
    {
        $rdv = Rdv::create([
            "date" => $request->date,
            "heure" => $request->heure,
            "client" => $request->client,
            "telephone" => $request->telephone,
            "salon_id" => $request->salon,
        ]);

        if(!$this->salon->clients()->where("telephone", $request->telephone)->exists())
        {
            $client = Client::create([
                "nom" => $request->client,
                "telephone" => $request->telephone,
                "anniversaire" => $request->anniversaire, //1970-04-24
                "salon_id" => $request->salon,
            ]);
        }

        return response()->json(new RdvResource($rdv));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RdvRequest $request
     * @param Rdv $rdv
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RdvRequest $request, Rdv $rdv)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->rdvs()->where("id", $rdv->id)->update([
            "date" => $request->date,
            "heure" => $request->heure,
            "client" => $request->client,
            "telephone" => $request->telephone,
        ]))
        {
            return response()->json([
                "message" => "Le RDV n'existe pas ou a été supprimé"
            ], 404);
        }

        $rdv = $rdv->fresh();

        return response()->json(new RdvResource($rdv));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rdv $rdv
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Rdv $rdv)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->rdvs()->where("id", $rdv->id)->delete())
        {
            return response()->json([
                "message" => "Le RDV n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
