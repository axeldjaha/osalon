<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class DepenseResource extends JsonResource
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
            "objet" => $this->objet,
            "montant" => intval($this->montant),
            "date" => $this->date_depense != null ? date("d/m/Y", strtotime($this->date_depense)) : null,
            "salon_id" => $this->salon_id,
        ];
    }
}
