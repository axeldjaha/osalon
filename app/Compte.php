<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    protected $fillable = [

    ];

    public function salons()
    {
        return $this->hasMany(Salon::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function abonnement()
    {
        return $this->hasOne(Abonnement::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
