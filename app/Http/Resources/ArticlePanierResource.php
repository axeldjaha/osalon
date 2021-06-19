<?php

namespace App\Http\Resources;

use App\Article;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticlePanierResource extends JsonResource
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
            "canceled" => $this->pivot->canceled == 1,
            "quantite" => $this->pivot->quantite,
            "article" => new ArticleResource(Article::find($this->id)),
        ];
    }
}
