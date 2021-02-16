<?php

namespace App\Http\Resources;

use App\Salon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class DepenseSalonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $salon = Salon::find($this->salon_id);

        return [
            "depense" => $this->depense,
            "salon" => [
                "id" => $salon->id,
                "nom" => $salon->nom,
            ]
        ];
    }
}
