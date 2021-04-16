<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        "montant",
        "abonnement_id",
        "salon_id",
    ];

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
