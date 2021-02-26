<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

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
