<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
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
        SELECT SUM(articles.prix * article_panier.quantite) AS total
        FROM paniers
        INNER JOIN article_panier ON article_panier.panier_id = paniers.id
        INNER JOIN articles ON articles.id = article_panier.article_id
        WHERE paniers.id = ? AND article_panier.canceled = ?";
        $result = DB::select($query, [$this->id, false]);

        return [
            "id" => $this->id,
            "total" => $result[0]->total,
            "date" => date("d/m/Y à H:i", strtotime($this->date)),
            "article_paniers" => ArticlePanierResource::collection($this->articles),
            "salon_id" => $this->salon_id,
        ];
    }
}
