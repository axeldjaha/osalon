<?php

namespace App\Http\Controllers\Api;

use App\Abonnement;
use App\Http\Requests\SalonRequest;
use App\Http\Resources\LavageDataResource;
use App\Http\Resources\SalonResource;
use App\Salon;
use App\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SalonController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $salons = $this->user->salons()->orderBy("nom")->get();

        return response()->json(SalonResource::collection($salons));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SalonRequest $request
     * @return Response
     */
    public function store(SalonRequest $request)
    {
        $salon = Salon::create([
            "nom" => $request->nom,
            "adresse" => $request->adresse,
        ]);

        $salon->update([
            "pid" => date("y") . date("m") . $salon->id,
        ]);

        $this->user->salons()->sync([$salon->id], false);

        Abonnement::create([
            "date" => Carbon::now(),
            "echeance" => Carbon::now()->addDays(10),
            "salon_id" => $salon->id,
        ]);

        return response()->json(new SalonResource($salon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SalonRequest $request
     * @param \App\Salon $salon
     * @return Response
     */
    public function update(SalonRequest $request, Salon $salon)
    {
        if(!$this->user->salons()->where("id", $salon->id)->update([
            "nom" => $request->nom,
            "adresse" => $request->adresse,
        ]))
        {
            return response()->json([
                "message" => "Le salon n'existe pas ou a été supprimé"
            ], 404);
        }

        $salon = $salon->refresh();

        return response()->json(new SalonResource($salon));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Service $salon
     * @return Response
     */
    public function destroy(Salon $salon)
    {
        if($this->user->salons()->count() == 1)
        {
            return response()->json([
                "message" => "Vous ne pouvez pas supprimer le seul salon de votre compte."
            ], 404);
        }

        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->user->salons()->where("id", $salon->id)->delete())
        {
            return response()->json([
                "message" => "Le salon n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }

}
