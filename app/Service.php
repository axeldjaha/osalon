<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = [
        "nom",
        "prix",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function paniers()
    {
        return $this->belongsToMany(Panier::class)->withPivot(["canceled", "prix"]);
    }
}
