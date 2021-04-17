<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    public static $TYPE_MENSUEL = "Mois";
    public static $TYPE_ANNUEL = "An";
    /**
     * Nombre de jour d'essai
     * @var int
     */
    public static $TRIAL = 7;

    protected $fillable = [
        "echeance",
        "montant",
        "type_id",
        "compte_id",
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

}
