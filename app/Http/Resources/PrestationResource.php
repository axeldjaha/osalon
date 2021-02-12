<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PrestationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->mois != null) {
            $mois = $request->mois;
        }
        elseif ($this->mois != null) {
            $mois = $this->mois;
        }
        else {
            $mois = Carbon::now()->month;
        }

        return [
            "id" => $this->id,
            "total" => intval($this->total),
            "date" => date("d/m/Y à H:i", strtotime($this->created_at)),
            "services" => ServiceResource::collection($this->services()->orderBy("nom")->get()),
            "salon_id" => $this->salon_id,
        ];
    }
}
