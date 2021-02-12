<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Jobs\MailJob;
use App\Mail\NewAccount;
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
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:admins',
        ]);

        $string = '1234567890ABCDEFGHJKLMNPQRSTUVWXYZabcefghjklmnpqrstuvwxyz';
        $password = Str::upper(substr(str_shuffle($string), 0, 6));

        $user = Admin::create([
            'name' => htmlspecialchars($request->name),
            'email' => htmlspecialchars($request->email),
            'password' => bcrypt($password),
        ]);

        if($request->has('permissions'))
        {
            $user->syncPermissions($request->permissions);
        }

        $data['email'] = $user->email;
        $data['password'] = $password;
        $data['espace'] = "Administration";

        Queue::push(new MailJob($user->email, new NewAccount($data)));

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Compte créé avec succès!');

        return redirect()->route('admin.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data["title"] = "Compte utilisateur";
        $data["active"] = "user";

        if(Auth::id() == $id)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', "Vous n'êtes pas autorisé à effectuer cette action.");
            return back();
        }

        $data["user"] = Admin::findOrFail(htmlspecialchars($id));
        $data["permissions"] = Permission::all();

        return view("admin.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        if(Auth::id() == $id)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', "Vous n'êtes pas autorisé à effectuer cette action.");
            return back();
        }

        $user = Admin::findOrFail(htmlspecialchars($id));

        if($request->has('permissions'))
        {
            $user->syncPermissions($request->permissions);
        }

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Mise à jour effectuée avec succès!');

        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = Admin::findOrFail(htmlspecialchars($id));
        if(Auth::id() == $id || $user->hasRole("super-admin"))
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', "Vous n'êtes pas autorisé à effectuer cette action.");
            return back();
        }
        if(Admin::destroy(htmlspecialchars($id)))
        {
            session()->flash('type', 'alert-success');
            session()->flash('message', 'Suppression effectuée avec succès!');
        }

        return redirect()->route('admin.index');
    }

}
