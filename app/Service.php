<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        "nom",
        "tarif",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function prestations()
    {
        return $this->belongsToMany(Prestation::class);
    }
}
