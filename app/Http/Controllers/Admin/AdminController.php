<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Jobs\MailJob;
use App\Jobs\SendSMS;
use App\Mail\NewAccount;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function index()
    {
        $data["title"] = "Admins";
        $data["active"] = "user";

        $data["users"] = Admin::orderBy('name')->get();

        return view("admin.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["title"] = "Créer admin";
        $data["active"] = "admin";

        $data["permissions"] = Permission::all();

        return view("admin.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            "telephone" => "required|unique:admins",
            'email' => 'required|email|unique:admins',
        ]);

        $password = User::generatePassword($request->telephone);

        $user = Admin::create([
            'name' => $request->name,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);

        //Envoi du mot de passe par SMS
        $messageBody = "Votre mot de passe est: $password";
        $to = [$request->telephone];

        $message = new Message();
        $message->setBody($messageBody);
        $message->setTo($to);
        $message->setIndicatif("225");
        $message->setSender(config("app.sms_sender_osalon"));
        Queue::push(new SendSMS($message));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Compte administrateur créé avec succès!');

        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin)
    {
        if(Auth::user()->id == $admin->id || $admin->email == "paxeldp@gmail.com")
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', "Vous ne pouvez pas supprimer ce compte.");
            return back();
        }

        $admin->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route('admin.index');
    }

}
