<?php

namespace App\Http\Resources;

use App\Offre;
use App\OffreSms;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AbonnementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "montant" => $this->montant,
            "echeance" => Carbon::parse($this->created_at)->addDays($this->type->validity)->format("d/m/Y"),
            "type" => new TypeResource($this->type),
            "expired" => Carbon::parse($this->created_at)
                ->addDays($this->type->validity)
                ->lessThan(Carbon::now()),
        ];
    }
}
