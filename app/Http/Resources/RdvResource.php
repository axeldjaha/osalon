<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            "date" => $this->date,
            "jour" => date("d", strtotime($this->date)),
            "mois" => strtoupper(Carbon::parse($this->date)->locale("fr_FR")->isoFormat('MMM')),
            "date_iso_format" => Carbon::parse($this->date)->locale("fr_FR")->isoFormat('dddd DD MMMM'),
            "heure" => $this->heure != null ? date("H:i", strtotime($this->heure)) : null,
            "client" => $this->client,
            "telephone" => $this->telephone,
            "salon_id" => $this->salon_id,
        ];
    }
}
