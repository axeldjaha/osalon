<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "Transactions";
        $data["active"] = "transaction";

        $data["transactions"] = Transaction::orderBy('id', 'desc')->where("statut", Transaction::$STATUT_TERMINE)->take(100)->get();

        return view("transaction.index", $data);
    }
}
