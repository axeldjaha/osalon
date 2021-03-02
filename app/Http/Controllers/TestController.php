<?php

namespace App\Http\Controllers;


use App\Salon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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





        //DB::table("users")->update(["password" => bcrypt("2909")]);
        /*Client::each(function ($client){
            $client->update([
                "telephone" => ["01", "05", "07"][rand(0, 2)] . (51197890 + $client->id),
            ]);
        });*/

        return config("app.name");
    }

}
