<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data["title"] = "Tableau de bord";
        $data["active"] = "dashboard";


        return view('home', $data);
    }
}
