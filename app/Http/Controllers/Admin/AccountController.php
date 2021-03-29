<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Rules\MatchPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AccountController extends Controller
{
    public function acces()
    {
        $data['title'] = 'Modifier mes accès';
        $data['active'] = '';

        $data['user'] = Auth::user();
        $data['extends'] = Auth::user()->formateur != null ? "layouts.formateur.app" : "layouts.app";

        return view("account.acces", $data);
    }

    public function updateAcces(Request $request)
    {
        $this->validate($request, [
            "password" => ['required', new MatchPassword],
            "nouveau_mot_de_passe" => "required|confirmed",
            "nouveau_mot_de_passe_confirmation" => "required",
        ]);

        auth()->user()->update([
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

        $data['user'] = Auth::user();
        $data['extends'] = Auth::user()->formateur != null ? "layouts.formateur.app" : "layouts.app";

        return view("account.infos", $data);
    }

    public function updateInfos(Request $request)
    {
        $this->validate($request, [
            "nom" => "required",
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        $data["name"] = $request->nom;

        if($request->hasFile('photo'))
        {
            $destinationPath = "app/public/users";

            if($user->photo != null && file_exists(storage_path("$destinationPath/$user->photo"))) {
                unlink(storage_path("$destinationPath/$user->photo"));
            }

            if (!file_exists(storage_path($destinationPath))) {
                Storage::makeDirectory("public/users", 0775, true); //creates directory
            }

            $image = $request->file('photo');
            $data["photo"] = md5($user->id . time()).'.'.$image->extension();
            $img = Image::make($image->path());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path($destinationPath).'/'.$data["photo"]);
        }

        $user->update($data);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Vos informations ont été modifiées avec succès!');

        return back();
    }
}
