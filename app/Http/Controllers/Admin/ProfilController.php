<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Rules\MatchPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfilController extends Controller
{

    public function acces()
    {
        $data['title'] = 'Modifier mes accès';
        $data['active'] = '';

        return view("profil.acces", $data);
    }

    public function updateAcces(Request $request)
    {
        $validateEmail = $request->email != auth()->user()->email ? "required|email|unique:users" : "required|email";

        $this->validate($request, [
            "email" => $validateEmail,
            "password" => ['required', new MatchPassword],
            "nouveau_mot_de_passe" => "required|confirmed",
            "nouveau_mot_de_passe_confirmation" => "required",
        ]);

        Admin::find(auth()->user()->id)->update([
            'email'=> $request->email,
            'password'=> bcrypt($request->nouveau_mot_de_passe),
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Vos accès ont été modifiés avec succès!');

        return back();
    }

    public function infos()
    {
        $data['title'] = 'Modifier mes informations';
        $data['active'] = '';

        return view("profil.infos", $data);
    }

    public function updateInfos(Request $request)
    {
        $this->validate($request, [
            "nom" => "required",
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data["name"] = $request->nom;

        if($request->hasFile('photo'))
        {
            $file = $request->file('photo');
            $filename = Str::slug(auth()->user()->name) . time() . '.' . $file->getClientOriginalExtension();
            $data["avatar"] = $filename;
            if(auth()->user()->avatar != null && file_exists(storage_path("app/public/avatars/".auth()->user()->avatar)))
            {
                unlink(storage_path("app/public/avatars/".auth()->user()->avatar));
            }
            $file->storeAs('public/avatars', $filename);
        }
        auth()->user()->update($data);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Vos informations ont été modifiées avec succès!');

        return back();
    }

}
