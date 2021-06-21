<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $fillable = [
        "nom",
        "prix_unitaire",
        "quantite",
        "canceled",
        "date",
        "panier_id",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function panier()
    {
        return $this->belongsTo(Panier::class);
    }
}
