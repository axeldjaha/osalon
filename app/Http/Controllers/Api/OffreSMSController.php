<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AbonnementResource;
use App\Http\Resources\OffreSMSResource;
use App\OffreSms;

class OffreSMSController extends ApiController
{
    public function index()
    {
        return response()->json(OffreSMSResource::collection(OffreSms::orderBy("quantite")->get()));
    }
}
