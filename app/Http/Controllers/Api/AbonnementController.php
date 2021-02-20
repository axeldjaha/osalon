<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AbonnementResource;

class AbonnementController extends ApiController
{
    public function index()
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "pid" => $salon->pid,
                "abonnement" => new AbonnementResource($salon->abonnements()->orderBy("id", "desc")->first()),
            ];
        }

        return response()->json($salons);
    }
}
