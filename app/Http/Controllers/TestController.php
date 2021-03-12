<?php

namespace App\Http\Controllers;


use App\Client;
use App\Jobs\SendSMS;
use App\Salon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
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

        $message = "Nouveau compte
Salon: $salon->nom
Adresse: $salon->adresse
Semaine: 15
Mois: 60";
        $sms = new stdClass();
        $sms->to = ["0758572785"];
        $sms->message = "Ok?";
        //Queue::push(new SendSMS($sms));


        //DB::table("users")->update(["password" => bcrypt("2909")]);
        /*Client::each(function ($client){
            $client->update([
                "telephone" => ["01", "05", "07"][rand(0, 2)] . (51000000 + $client->id),
            ]);
        });*/


        $time = Carbon::now()->format("H:i:s");

        $date = Carbon::now();
        $comptesDeLaSemaine = Salon::whereBetween("created_at", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $comptesDuMois = Salon::whereYear("created_at", $date->year)->whereMonth("created_at", $date->month)->count();
        $message = "Nouveau compte" .
        "%0ASalon: $time" .
        "%0AAdresse: $time" .
        "%0ASemaine: $time" .
        "%0AMois: $time";
        $sms = new stdClass();
        $sms->to = ["58572785"];
        $sms->message = $message;
        //Queue::push(new SendSMS($sms));

        return config("app.name");
    }

}
