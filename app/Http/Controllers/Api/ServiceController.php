<?php

namespace App\Http\Controllers\Api;

use App\Fakedata;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\DepenseSalonResource;
use App\Http\Resources\PrestationServiceResource;
use App\Http\Resources\SalonResource;
use App\Http\Resources\ServiceResource;
use App\Salon;
use App\Service;
use App\Tarification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
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
         * renvyer 204 pour retouner à Index et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json(new SalonResource(new Salon()), 204);
        }

        return response()->json(ServiceResource::collection($salon->services()->orderBy("nom")->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServiceRequest $request
     * @return Response
     */
    public function store(ServiceRequest $request)
    {
        $service = Service::create([
            "nom" => $request->nom,
            "tarif" => $request->tarif,
            "salon_id" => $request->salon,
        ]);

        return response()->json(new ServiceResource($service));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Service $service
     * @return Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->services()->where("id", $service->id)->update([
            "nom" => $request->nom,
            "tarif" => $request->tarif
        ]))
        {
            return response()->json([
                "message" => "Le prestation n'existe pas ou a été supprimé"
            ], 404);
        }

        $service = $service->fresh();

        return response()->json(new ServiceResource($service));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Service $service
     * @return Response
     */
    public function destroy(Service $service)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->services()->where("id", $service->id)->delete())
        {
            return response()->json([
                "message" => "La prestation n'existe pas ou a été supprimée"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
