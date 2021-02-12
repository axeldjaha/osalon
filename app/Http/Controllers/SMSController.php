<?php

namespace App\Http\Controllers;

use App\Client;
use App\Fichier;
use App\FichierProspect;
use App\Http\Resources\Web\ClientResource;
use App\Http\Resources\Web\FichierProspectResource;
use App\Http\Resources\Web\PressingResource;
use App\Jobs\SendSMS;
use App\Jobs\SMSDispatcher;
use App\Salon;
use App\Prospect;
use App\Sms;
use App\SMSCounter;
use App\User;
use Eusebiu\JavaScript\Facades\ScriptVariables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use stdClass;
use function GuzzleHttp\Promise\all;

class SMSController extends Controller
{
    private $SEND_TO_CLIENTS = "Clients";
    private $SEND_TO_LAVAGES = "Pressings";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "SMS - Boîte d'envoi";
        $data["active"] = "sms";

        $data["clientSMS"] = Sms::where("to", $this->SEND_TO_CLIENTS)->count();
        $data["salonSMS"] = Sms::where("to", $this->SEND_TO_LAVAGES)->count();
        $data["all"] = Sms::count();
        $data["smsSent"] = Sms::orderBy("id", 'desc')->get();

        return view("sms.index", $data);
    }

    public function loadProspects()
    {
        return FichierProspectResource::collection(FichierProspect::orderBy("nom")->get());
    }

    public function loadClients()
    {
        return ClientResource::collection(User::orderBy("name")->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["title"] = "Envoi SMS";
        $data["active"] = "sms";

        $data["fichiers"] = FichierProspect::orderBy("nom")->get();
        $data["clients"] = User::where("role", User::$ROLE_SUPERVISEUR)->orderBy("name")->get();
        $data["salons"] = Salon::orderBy("nom")->get();

        ScriptVariables::add('prospects', Prospect::count());
        ScriptVariables::add('clients', User::count());

        ScriptVariables::add('defaultUrl', route("sms.recipients.prospects"));

        return view("sms.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data["telephone"] = $this->formatTelephone($request->telephone);
        if($request->has("unique"))
        {
            $request->replace($data);
            $dataValidator = [
                "unique" => "nullable",
                "telephone" => "required_if:unique,on|regex:/[0-9]{8}/",
                "filter" => "required_unless:unique,on",
                "recipients" => "required_unless:unique,on",
                "message" => "required",
            ];
        }
        else
        {
            $dataValidator = [
                "unique" => "nullable",
                "telephone" => "required_if:unique,on", //regex:/[0-9]{8}/
                "filter" => "required_unless:unique,on",
                "recipients" => "required_unless:unique,on",
                "message" => "required",
            ];
        }

        $this->validate($request, $dataValidator, [
            "telephone.required_if" => "Numéro de téléphone non renseigné",
            "recipients.required_unless" => "Aucun destinataire coché",
        ]);

        $to = [];

        $smsTo = [];

        if($request->exists("unique") && $request->telephone != null)
        {
            $smsTo = $request->telephone;
            $to = [$request->telephone];
        }
        else
        {
            switch ($request->filter)
            {
                case "prospects":
                    $smsTo = "Prospects";
                    $to = Prospect::whereIn("fichier_prospect_id", $request->recipients)->pluck("telephone")->toArray();
                    break;

                case "clients":
                    $smsTo = "Clients";
                    $to = User::pluck("telephone")->toArray();
                    break;
            }
        }

        $to = array_unique($to);

        if(count($to) == 0)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', 'Aucun destinataire trouvé');
            return back()->withInput();
        }

        $message = str_replace("\r\n", "\n", trim($request->message));
        $smsCounter = new SMSCounter();
        $smsInfo = $smsCounter->count($message);
        $part = $smsInfo->messages * count($to);

        Sms::create([
            "to" => $smsTo,
            "message" => $message,
            "sent_by" => Auth::user()->name,
            "part" => $part,
        ]);

        Queue::push(new SMSDispatcher($to, $message));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Envoi effectué avec succès!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Sms $sms
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        Sms::destroy($id);
        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("sms.index");
    }

    public function destroyChecked(Request $request)
    {
        if(Sms::destroy($request->checked))
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', 'Suppression effectuée avec succès!');
        }

        return back();
    }

    public function formatTelephone($telephone)
    {
        $phoneNumber = trim($telephone);
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = str_replace('-', '', $phoneNumber);
        $phoneNumber = str_replace('/', '', $phoneNumber);
        $phoneNumber = substr($phoneNumber, 0, 8);
        return Str::length($phoneNumber) < 8 ? '0'.$phoneNumber : $phoneNumber;
    }


}
