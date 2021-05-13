<?php

namespace App\Http\Controllers;


use App\Compte;
use App\Contact;
use App\Jobs\BulkSMS;
use App\Jobs\SendSMS;
use App\Panier;
use App\Salon;
use App\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Mediumart\Orange\SMS\Http\SMSClient;
use Mediumart\Orange\SMS\Http\SMSClientRequest;
use Mediumart\Orange\SMS\SMS;
use SameerShelavale\PhpCountriesArray\CountriesArray;

class TestController extends Controller
{
    private $user;
    private $salon;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test(Request $request)
    {
        $salon = Salon::first();
        $compte = Compte::find(6);
        $panier = Panier::first();





        /*$sms = new \stdClass();
        $sms->message = date("Y-m-d H:i:s");
        $sms->to = ["0153791279"];
        Queue::push(new SendSMS($sms));*/

        //DB::table("users")->update(["password" => bcrypt("2909")]);

        /*Client::each(function ($client){
            $client->update([
                "telephone" => ["01", "05", "07"][rand(0, 2)] . (51000000 + $client->id),
            ]);
        });*/

        return config("app.name");
    }


    public function orangeSMS()
    {
        $config = array(
            'clientId' => 'C0gqKzmECouAf1VMFeg3fkfPruxi5wnV',
            'clientSecret' => 'fZJtzYAMZTDs9vLm'
        );


    }

    public function sendToProspects()
    {
        $to = [
            "0758572785",
            //"0153791279",
            //"0759457081", //Poz'Ongles
        ];
        $message = "Toute l'équipe de O'salon vous souhaite une très bonne fete du travail!" .
            "\nO'salon, l'appli mobile numéro 1 pour gérer votre salon à distance, disponibe sur playstore." .
            "\nhttps://play.google.com/store/apps/details?id=polaris.osalon";
        //Queue::push(new BulkSMS($message, $to, env("SMS_SENDER")));
    }

}
