<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Admin;
use App\Client;
use App\FichierProspect;
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
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function test()
    {
        //DB::table("users")->update(["password" => bcrypt("2909")]);
        $user = User::where("email", "paxeldp@gmail.com")->first();
        $salon = Salon::first();


        return config("app.name");
    }

}
