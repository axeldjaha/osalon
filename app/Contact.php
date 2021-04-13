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
        if(strlen($phoneNumber) == 8)
        {
            $prefix = substr($phoneNumber, 1, 1);
            switch ($prefix)
            {
                case "0":
                case "1":
                case "2":
                case "3":
                    $phoneNumber = "01" . $phoneNumber;
                    break;

                case "4":
                case "5":
                case "6":
                    $phoneNumber = "05" . $phoneNumber;
                    break;

                case "7":
                case "8":
                case "9":
                    $phoneNumber = "07" . $phoneNumber;
                    break;
            }
        }

        return $phoneNumber;
    }
}
