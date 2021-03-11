<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    /**
     * Nombre de jour d'essai
     * @var int
     */
    public static $TRIAL = 7;

    protected $fillable = [
        "date",
        "echeance",
        "montant",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

}
