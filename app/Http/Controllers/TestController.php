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


        $sms = new \stdClass();
        $message =
            "Votre mot de passe est: 279K" .
            "\nTéléchargez l'application " . config("app.name") . " sur playstore." .
            "\n" . config("app.playstore");
        $sms->to = ["58572785"];
        //Queue::push(new SendSMS($sms));

        $url = "https://play.google.com/store/apps/details?id=ci.polaris.osalon";
        var_dump(urlencode($url));
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        var_dump(str_replace($entities, $replacements, urlencode($url)));


        //DB::table("users")->update(["password" => bcrypt("2909")]);
        /*Client::each(function ($client){
            $client->update([
                "telephone" => ["01", "05", "07"][rand(0, 2)] . (51000000 + $client->id),
            ]);
        });*/

        return config("app.name");
    }

}
