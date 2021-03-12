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


        $year = date("y");
        $month = date("m");
        $pid = $year . $month . Salon::whereMonth("created_at", Carbon::now()->month)->count();
        dd($pid);

        return config("app.name");
    }

}
