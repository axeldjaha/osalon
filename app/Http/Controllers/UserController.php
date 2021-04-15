<?php

namespace App\Http\Controllers;

use App\Operateur;
use App\Parametre;
use App\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "Users";
        $data["active"] = "user";

        $data["users"] = User::orderBy("id", "desc")->get();

        return view("user.index", $data);
    }

    public function show(User $user)
    {
        $data["title"] = "Users";
        $data["active"] = "user";
        $data["tab"] = "info";

        $data["user"] = $user;

        return view("user.show", $data);
    }

    public function resetPassword(User $user)
    {
        $user->update([
            "password" => bcrypt(User::generatePassword($user->telephone))
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Mot de passe réinitialisé avec succès!');

        return redirect()->route("user.index");
    }

    public function acces(User $user)
    {
        $data["title"] = "Users #$user->id :: Accès";
        $data["active"] = "User";
        $data["tab"] = "acces";

        $data["user"] = $user;

        return view("user.acces", $data);
    }

    public function lock(User $user)
    {
        $user->update([
            "activated" => false,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Utilisateur désactivé avec succès!');

        return back();
    }

    public function unlock(User $user)
    {
        $user->update([
            "activated" => true,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Utilisateur activé avec succès!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("user.index");
    }
}
