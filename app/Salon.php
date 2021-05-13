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
        "compte_id",
        "pays_id",
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    /**
     * Un salon peut être associé à plusieurs users
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, "pays_id");
    }

    public function paniers()
    {
        return $this->hasMany(Panier::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
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

}
