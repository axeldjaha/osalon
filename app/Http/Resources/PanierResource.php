<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PanierResource extends JsonResource
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
            "date" => date("d/m/Y à H:i", strtotime($this->date)),
            "items" => ItemResource::collection($this->items()->orderBy("id", "desc")),
            "salon_id" => $this->salon_id,
        ];
    }
}
