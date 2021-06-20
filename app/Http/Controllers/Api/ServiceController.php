<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Salon;
use App\Service;
use Illuminate\Http\Request;

class ServiceController extends ApiController
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
                "services" => ServiceResource::collection($salon->services()->orderBy("nom")->get()),
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

        return response()->json(ServiceResource::collection($salon->services()->orderBy("nom")->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ServiceRequest $request)
    {
        $service = Service::create([
            "nom" => $request->nom,
            "prix" => $request->prix,
            "salon_id" => $this->salon->id,
        ]);

        return response()->json(new ServiceResource($service));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServiceRequest $request
     * @param Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ServiceRequest $request, Service $service)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->services()->where("id", $service->id)->update([
            "nom" => $request->nom,
            "prix" => $request->prix
        ]))
        {
            return response()->json([
                "message" => "Le ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        $service = $service->fresh();

        return response()->json(new ServiceResource($service));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Service $service)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->services()->where("id", $service->id)->delete())
        {
            return response()->json([
                "message" => "La ressource n'existe pas ou a été supprimée"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
