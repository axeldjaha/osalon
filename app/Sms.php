<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $fillable = [
        "message",
        "recipient",
        "date",
        "user",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
