<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    public $fillable = [
        "total",
        "reference",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
