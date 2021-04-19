<?php

namespace App\Http\Controllers;

use App\Compte;
use App\Jobs\SendSMS;
use App\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use stdClass;

class RechareSMSController extends Controller
{

    /**
     * Create
     *
     * @param Compte $compte
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Compte $compte)
    {
        $data["title"] = "Comptes";
        $data["active"] = "compte";

        $data["compte"] = $compte;

        return view("recharge.create", $data);
    }

    /**
     * Store
     *
     * @param Request $request
     * @param Compte $compte
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Compte $compte)
    {
        $this->validate($request, [
            "sms_balance" => "required|numeric",
        ]);

        $compte->increment("sms_balance", $request->sms_balance);

        $message = "Votre compte SMS a été rechargé avec succès!";
        $sms = new stdClass();
        $sms->to = [$compte->users()->first()->telephone];
        $sms->message = $message;
        Queue::push(new SendSMS($sms));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Rechargement effectué avec succès!');

        return redirect()->route("compte.show", $compte);
    }

}
