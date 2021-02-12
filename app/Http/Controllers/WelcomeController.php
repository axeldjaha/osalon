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
}
