<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function cgu()
    {
        return response()->file(public_path() . "/docs/cgu.pdf");
    }

    public function download()
    {
        return response()->file(public_path() . "/apk/Osalon-v1.0.0.apk");
    }

    public function privacy()
    {
        return view("privacy");
    }

}
