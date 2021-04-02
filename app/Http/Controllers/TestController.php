<?php

namespace App\Http\Controllers;


use App\Client;
use App\Http\Resources\OffreSMSResource;
use App\Http\Resources\RdvResource;
use App\Jobs\SendSMS;
use App\OffreSms;
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

        $query = "
        SELECT date,
          COUNT(*) AS total
        FROM rdvs
        WHERE salon_id = ? AND date >= ?
        GROUP BY date
        ORDER BY date";
        $rdvs = DB::select($query, [$salon->id, Carbon::today()]);
        $data = [];
        foreach ($rdvs as $rdv)
        {
            $date = Carbon::parse($rdv->date)->locale("fr_FR")->isoFormat('ddd DD MMMM');
            //var_dump($date);
            $data[] = [
                "date" => $rdv->date,
                "date_iso_format" => Carbon::parse($rdv->date)->locale("fr_FR")->isoFormat('ddd DD MMMM'),
                "total" => $rdv->total,
                "salon_id" => $rdv->salon_id,
            ];
        }

        var_dump($data);



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

}
