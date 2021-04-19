<?php

namespace App\Http\Controllers;


use App\Client;
use App\Compte;
use App\Contact;
use App\Http\Resources\OffreSMSResource;
use App\Http\Resources\RdvResource;
use App\Jobs\SendSMS;
use App\OffreSms;
use App\Salon;
use App\SKien\VCard\VCard;
use App\Type;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Osms\Osms;
use App\SKien\VCard\VCardAddress;
use App\SKien\VCard\VCardContact;
use stdClass;

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



        //DB::table("users")->update(["password" => bcrypt("2909")]);

        /*Client::each(function ($client){
            $client->update([
                "telephone" => ["01", "05", "07"][rand(0, 2)] . (51000000 + $client->id),
            ]);
        });*/


        /*$time = Carbon::now()->format("H:i:s");
        $date = Carbon::now();
        $comptesDeLaSemaine = Salon::whereBetween("created_at", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $comptesDuMois = Salon::whereYear("created_at", $date->year)->whereMonth("created_at", $date->month)->count();
        $message = "Nouveau compte" .
        "\nSalon: $time" .
        "\nAdresse: $time" .
        "\nSemaine: $time" .
        "\nMois: $time";
        $message = "Téléchargez l'application\n" . config("app.playstore");
        $sms = new stdClass();
        $sms->to = ["58572785"];
        $sms->message = $message;
        //Queue::push(new SendSMS($sms));*/


        return config("app.name");
    }

    public function exportContactToVcf()
    {
        // create object
        $oVCard = new VCard();

        $index = 0;
        $except = [
            "47299508", //Onglerie Elo
            "07006225", //Perfect Nails
        ];
        $data = [];
        $contacts = Contact::whereNotIn("telephone", $except)->pluck("telephone")->toArray();
        foreach ($contacts as $contact)
        {
            $data[] = strlen($contact) > 8 ? substr($contact, 2) : $contact;
        }
        $contacts = array_unique($data);
        foreach ($contacts as $telephone)
        {
            $index++;
            if($index < 10)
            {
                $numero = "00" . $index;
            }
            elseif($index >= 10 && $index < 100)
            {
                $numero = "0" . $index;
            }
            elseif($index >= 100)
            {
                $numero = $index;
            }
            $name = "O' - Prospect Nº " . $numero;
            // just create new contact
            $oContact = new VCardContact();
            $oContact->setName($name, null);
            $telephone = strlen($telephone) > 8 ? substr($telephone, 2) : $telephone;
            $oContact->addPhone($telephone, VCard::WORK, false);
            // insert contact
            $oVCard->addContact($oContact);
        }

        // and write to file
        return $oVCard->write("O'-prospects.vcf", false);
    }

    public function orangeSMS()
    {
        $config = array(
            'clientId' => 'C0gqKzmECouAf1VMFeg3fkfPruxi5wnV',
            'clientSecret' => 'fZJtzYAMZTDs9vLm'
        );

        $osms = new Osms($config);

        // retrieve an access token
        $response = $osms->getTokenFromConsumerKey();

        if (!empty($response['access_token']))
        {
            $senderAddress = 'tel:+22558572785';
            $receiverAddress = 'tel:+22551197885';
            $message = 'Hello World!';
            $senderName = 'Optimus Prime';

            $osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
        }
        else
        {
            dd($response);
        }
    }

}
