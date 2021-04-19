<?php

namespace App\Http\Controllers\Api;

use App\Fakedata;
use App\Http\Requests\IndexPrestationRequest;
use App\Http\Requests\PrestationRequest;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\PrestationResource;
use App\Http\Resources\SalonResource;
use App\Prestation;
use App\Recette;
use App\Salon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PrestationController extends ApiController
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
            $prestations = $salon->prestations()
                ->whereDate('created_at', $request->date ?? Carbon::now())
                ->orderBy("created_at", "desc")
                ->get();

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "encaissements" => PrestationResource::collection($prestations),
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

        $depenses = $salon->prestations()
            ->whereDate('created_at', $request->date ?? Carbon::now())
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json(PrestationResource::collection($depenses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PrestationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PrestationRequest $request)
    {
        $prestation = Prestation::create([
            "total" => $request->total,
            "salon_id" => $this->salon->id,
        ]);

        foreach ($request->services as $service)
        {
            $prestation->services()->sync([$service["id"]], false);
        }

        //sleep(10);

        return response()->json(new DepenseResource(new Prestation()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Prestation $prestation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestation $prestation)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->prestations()->where("id", $prestation->id)->delete())
        {
            return response()->json([
                "message" => "La prestation n'existe pas ou a été supprimée."
            ], 404);
        }

        return response()->json(null, 204);
    }
}
