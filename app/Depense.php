<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    public static $PERMISSION_STORE = "depense.store";
    public static $PERMISSION_DELETE = "depense.delete";

    protected $fillable = [
        "objet",
        "montant",
        "date_depense",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
