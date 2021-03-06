<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SalonResource extends JsonResource
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
            "adresse" => $this->adresse,
            "telephone" => $this->telephone,
            "created_at" => date("d/m/Y", strtotime($this->created_at)),
            "articles" => ArticleResource::collection($this->articles()->orderBy("nom")->get()),
            "services" => ServiceResource::collection($this->services()->orderBy("nom")->get()),
            "clients" => ClientResource::collection($this->clients()->orderBy("nom")->get()),
            "users" => UserResource::collection($this->users()->orderBy("name")->get()),
        ];
    }
}
