<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        "montant",
        "abonnement_id",
        "compte_id",
    ];

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }
}
