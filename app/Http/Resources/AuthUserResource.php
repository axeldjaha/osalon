<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $salons = [];
        foreach ($this->salons as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "articles" => ArticleResource::collection($salon->articles()->orderBy("nom")->get()),
                "services" => ServiceResource::collection($salon->services()->orderBy("nom")->get()),
            ];
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "telephone" => $this->telephone,
            "email" => $this->email,
            "salons" => $salons,
            "token" => $this->token,
        ];
    }
}
