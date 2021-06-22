<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $fillable = [
        "nom",
        "prix",
        "stock",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function paniers()
    {
        return $this->belongsToMany(Panier::class)->withPivot("quantite");
    }
}
