<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            "intitule" => $this->intitule,
            "montant" => $this->montant,
            "validity" => $this->validity,
        ];
    }
}
