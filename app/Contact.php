<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        "nom",
        "telephone",
        "sms_groupe_id",
    ];

    public function groupe()
    {
        return $this->belongsTo(SmsGroupe::class);
    }

    public static function formatPhoneNumber($telephone)
    {
        $phoneNumber = preg_replace('/[\-.\s]/s','', $telephone);
        $phoneNumber = substr($phoneNumber, 0, 10);
        return $phoneNumber;
    }
}
