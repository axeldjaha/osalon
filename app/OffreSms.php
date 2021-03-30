<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffreSms extends Model
{
    protected $fillable = [
        "quantite",
        "prix",
    ];
}
