<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        "reference",
        "montant",
        "validite",
        "statut",
        "mode_paiement",
        "date",
        "salon_id",
        "offre_id",
    ];

    public static $STATUT_ATTENTE = "attente";
    public static $STATUT_TERMINE = "termine";
    public static $STATUT_ANNULE = "annule";

    public static $MODE_ESPECE = "Espèce";
    public static $MODE_PAIEMENT_LIGNE = "Paiement en ligne";
    public static $MODE_MOBILE_MONEY = "Mobile money";

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

}
