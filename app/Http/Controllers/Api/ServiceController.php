<?php

namespace App\Http\Controllers\Api;

use App\Fakedata;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\PrestationServiceResource;
use App\Http\Resources\ServiceResource;
use App\Service;
use App\Tarification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class ServiceController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $services = $this->salon->services()->orderBy("nom")->get();

        return response()->json(ServiceResource::collection($services));
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
