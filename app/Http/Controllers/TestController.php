<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Admin;
use App\Client;
use App\FichierProspect;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\DepenseSalonResource;
use App\Http\Resources\PrestationResource;
use App\Jobs\SendSMS;
use App\Jobs\SMSDispatcher;
use App\Salon;
use App\Operateur;
use App\Service;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PDF;
use stdClass;

class TestController extends Controller
{
    private $user;
    private $salon;

    public function __construct()
    {
        //$this->middleware('auth');
        //$this->user = User::where("email", "paxeldp@gmail.com")->first();
        //$this->salon = $this->user->salons()->first();
    }

    public function test(Request $request)
    {
        //DB::table("users")->update(["password" => bcrypt("2909")]);
        /*Client::each(function ($client){
            $client->update([
                "telephone" => ["01", "05", "07"][rand(0, 2)] . (51197890 + $client->id),
            ]);
        });*/



        return config("app.name");
    }

}
