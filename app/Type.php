<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public static $TYPE_ESSAI = "Essai";
    public static $TYPE_MENSUEL = "Mois";
    public static $TYPE_ANNUEL = "An";

    public static $MONTANT_ESSAI = 0;
    public static $MONTANT_MENSUEL = 10000;
    public static $MONTANT_ANNUEL = 35000;

    public static $VALIDITY_ESSAI = 7;
    public static $VALIDITY_MENSUEL = 30;
    public static $VALIDITY_ANNUEL = 365;

    protected $fillable = [
        "intitule",
        "montant",
        "validity",
    ];

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }
}
