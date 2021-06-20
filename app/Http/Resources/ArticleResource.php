<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            "libelle" => $this->libelle,
            "prix" => $this->prix,
            "stock" => $this->stock,
            "salon_id" => $this->salon_id,
        ];
    }
}
