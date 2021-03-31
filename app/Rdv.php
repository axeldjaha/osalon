<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    protected $fillable = [
        "date",
        "heure",
        "nom",
        "telephone",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
