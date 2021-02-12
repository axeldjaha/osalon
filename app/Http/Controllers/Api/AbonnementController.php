<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SalonResource;

class AbonnementController extends ApiController
{
    public function index()
    {
        return response()->json(SalonResource::collection($this->user->salons()->orderBy("nom")->get()));
    }
}
