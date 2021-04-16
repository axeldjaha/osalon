<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Salon extends Model
{

    protected $fillable = [
        "nom",
        "adresse",
        "telephone",
        "pid",
        "sms",
    ];

    /**
     * Un salon peut être associé à plusieurs users
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function abonnement()
    {
        return $this->hasOne(Abonnement::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function sms()
    {
        return $this->hasMany(Sms::class);
    }

    public function rdvs()
    {
        return $this->hasMany(Rdv::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
