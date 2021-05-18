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





        $sms = new \stdClass();
        $message = "Votre réabonnement a été effectué avec succès!" .
            "\nLéquipe de " . config("app.name");
        $sms->message = $message;
        $to = "+22674118090";
        //Queue::push(new SendSMS($sms, null, "225"));

        if(config("app.env") != "production")
        {
            SMSClientRequest::verify(false);
        }

        $token = Token::first();
        if ($token == null || Carbon::parse($token->valid_until)->lessThan(Carbon::now()))
        {
            $response = SMSClient::authorize(config("app.sms_client_id"), config("app.sms_client_secret"));
            DB::table("tokens")->truncate();
            $token = Token::create([
                "access_token" => $response["access_token"],
                "expires_in" => $response["expires_in"],
                "valid_until" => Carbon::now()->addSeconds($response["expires_in"]),
            ]);
        }

        $message = "Votre réabonnement a été effectué avec succès!" .
            "\nLéquipe de " . config("app.name");
        $sms->message = $message;
        $to = "+22674118090";

        $client = SMSClient::getInstance($token->access_token);
        $sms = new SMS($client);
        $sms->message($message)
            //->from('+2250758572785', $this->sender ?? config("app.sms_sender"))
            ->from('+2250758572785', null)
            ->to($to)
            ->send();



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
