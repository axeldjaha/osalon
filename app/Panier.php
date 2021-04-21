<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    protected $fillable = [
        "total",
        "date",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withPivot("quantite");
    }

}
