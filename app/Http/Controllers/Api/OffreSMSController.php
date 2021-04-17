<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AbonnementResource;
use App\Http\Resources\OffreSMSResource;
use App\OffreSms;

class OffreSMSController extends ApiController
{
    public function index()
    {
        $compte = [
            "sms_balance" => $this->compte->sms_balance,
            "offre_sms" => OffreSMSResource::collection(OffreSms::orderBy("quantite")->get()),
        ];
        return response()->json($compte);
    }
}
