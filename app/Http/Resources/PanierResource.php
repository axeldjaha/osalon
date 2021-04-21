<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            "total" => $this->total,
            "date" => date("d/m/Y à H:i", strtotime($this->date)),
            "article_panier" => ArticlePanierResource::collection($this->articles),
            "salon_id" => $this->salon_id,
        ];
    }
}
