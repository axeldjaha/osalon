<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RdvResource extends JsonResource
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
            "date" => date("d/m/Y", strtotime($this->date)),
            "heure" => date("H:i", strtotime($this->heure)),
            "nom" => $this->nom,
            "telephone" => $this->telephone,
            "salon_id" => $this->salon_id,
        ];
    }
}
