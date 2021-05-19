<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    protected $fillable = [
        "sms_balance",
        "pays_id",
    ];

    public function salons()
    {
        return $this->hasMany(Salon::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, "pays_id");
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
