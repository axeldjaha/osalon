<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGroupe extends Model
{
    protected $fillable = [
        "intitule",
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
