<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGroupe extends Model
{
    public static $USERS = "USERS";

    protected $fillable = [
        "intitule",
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
