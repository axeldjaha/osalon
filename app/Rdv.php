<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    protected $fillable = [
        "date",
        "heure",
        "client",
        "telephone",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
