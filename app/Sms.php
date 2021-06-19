<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    public static $PERMISSION_STORE = "sms.store";
    public static $PERMISSION_DELETE = "sms.delete";

    protected $fillable = [
        "to",
        "message",
        "user",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

}
