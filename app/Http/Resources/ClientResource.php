<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            "telephone" => $this->telephone,
            "anniversaire" => $this->anniversaire,
            "prefix" => mb_substr($this->telephone, 0, 2),
            "salon_id" => $this->salon_id,
        ];
    }
}
