<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AbonnementResource;

class AbonnementController extends ApiController
{
    public function index()
    {

        $abonnement = $this->compte->abonnements()->orderBy("id", "desc")->first();
        return response()->json(new AbonnementResource($abonnement));
    }
}
