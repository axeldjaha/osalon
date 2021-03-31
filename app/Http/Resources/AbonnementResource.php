<?php

namespace App\Http\Resources;

use App\Offre;
use App\OffreSms;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AbonnementResource extends JsonResource
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
            "montant" => $this->montant ?? Offre::orderBy("id", "desc")->first()->montant,
            "date" => date("d/m/Y", strtotime($this->date)),
            "echeance" => date("d/m/Y", strtotime($this->echeance)),
            "expired" => Carbon::parse($this->echeance)->lessThan(Carbon::now()),
        ];
    }
}
