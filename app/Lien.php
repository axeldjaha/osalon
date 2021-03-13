<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lien extends Model
{
    protected $fillable = [
        "url",
        "sms_id",
    ];

    public function sms()
    {
        return $this->belongsTo(Sms::class, "sms_id");
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
