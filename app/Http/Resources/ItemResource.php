<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            "id" => $this->id,
            "nom" => $this->nom,
            "prix_unitaire" => $this->prix_unitaire,
            "quantite" => $this->quantite,
            "date" => date("H:i", strtotime($this->date)),
            "panier_id" => $this->panier_id,
            "salon_id" => $this->salon_id,
        ];
    }
}
