<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable = [
        "objet",
        "montant",
        "date",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
