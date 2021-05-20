<?php

namespace App\Http\Controllers;

use App\BackSms;
use App\Jobs\BulkSMS;
use App\Message;
use App\SMSCounter;
use App\SmsGroupe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data['title'] = "Envoi SMS";
        $data['active'] = 'sms';

        $data['smses'] = BackSms::orderBy("id", "desc")->paginate(25);

        return view("sms.index", $data);
    }

    public function create(Request $request)
    {
        $data['title'] = "Envoi SMS";
        $data['active'] = 'sms';

        $data["groupes"] = SmsGroupe::orderBy("intitule")->get();

        return view("sms.create", $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "message" => "required",
            "groupes" => "required|array",
        ]);

        DB::transaction(function () use ($request)
        {
            $to = SmsGroupe::whereIn("sms_groupes.id", $request->groupes)
                ->join("contacts", "contacts.sms_groupe_id", "=", "sms_groupes.id")
                ->whereNotNull("contacts.telephone")
                ->pluck("contacts.telephone")
                ->toArray();

            $to = array_unique($to);

            if(count($to) == 0)
            {
                session()->flash('type', 'alert-danger');
                session()->flash('message', "Aucun contact n'a été trouvé.");
                return back()->withInput();
            }

            $messageBody = str_replace("\r\n", "\n", trim($request->message));
            $smsCounter = new SMSCounter();
            $smsInfo = $smsCounter->count($messageBody);
            $volume = $smsInfo->messages * count($to);

            $groupes = SmsGroupe::whereIn("sms_groupes.id", $request->groupes)->get();
            foreach ($groupes as $groupe)
            {
                BackSms::create([
                    "to" => $groupe->intitule,
                    "message" => $messageBody,
                    "user" => Auth::user()->name,
                ]);
            }

            $message = new Message();
            $message->setBody($messageBody);
            $message->setTo($to);
            $message->setIndicatif("225");
            $message->setSender(config("app.sms_sender_osalon"));
            //todo Queue::push(new BulkSMS($message));

        }, 1);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Envoi effectué avec succès!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        if(BackSms::destroy($request->smses))
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', 'Suppression effectuée avec succès!');
        }

        return redirect()->route("sms.index");
    }

}
