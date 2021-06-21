<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
        $query = "
        SELECT SUM(items.prix_unitaire * items.quantite) AS total
        FROM paniers
        INNER JOIN items ON items.panier_id = paniers.id
        WHERE paniers.id = ? AND items.canceled = ?";
        $result = DB::select($query, [$this->id, false]);

        return [
            "id" => $this->id,
            "date" => date("d/m/Y à H:i", strtotime($this->date)),
            "total" => $result[0]->total,
            "items" => ItemResource::collection($this->items()->orderBy("id", "desc")->get()),
            "salon_id" => $this->salon_id,
        ];
    }
}
