<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AbonnementResource;

class AbonnementController extends ApiController
{
    public function index()
    {
        return response()->json(new AbonnementResource($this->compte->abonnement));
    }
}
