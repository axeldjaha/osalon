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
        return [
            "id" => $this->id,
            "name" => $this->name,
            "telephone" => $this->telephone,
            "email" => $this->email,
            "role" => $this->role,
            "salons" => SalonResource::collection($this->salons()->orderBy("id", "desc")->get()),
            "token" => $this->token,
        ];
    }
}
