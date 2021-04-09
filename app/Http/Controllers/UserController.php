<?php

namespace App\Http\Controllers;

use App\Operateur;
use App\Parametre;
use App\User;

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

        $data["users"] = User::orderBy("name")->get();

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
        /**
         * 1. x = les 4 derniers chiffres du numéro de téléphone
         * 2. si valeur(x) = 0, x = 1
         * 3. y = les 2 chiffres aprés le premier chiffre du numéro de téléphone
         * 4. z = x * y
         * 5. p = les 4 premiers chiffres de z
         * 6. si longueur(p) inférieur à 4, ajouter des zéro pour obtenir 4 chiffres
         * p est le mot de passe
         */
        $x = substr($user->telephone, -4); //1
        $x == 0 ? $x = 1 : $x; //2
        $y = substr($user->telephone, 1, 2); //3
        $z = $x * $y; //4
        $p = substr($z, 0, 4); //5
        if(strlen($p) < 4)
        {
            for ($i = strlen($p); $i < 4; $i++)
            {
                $p .= 0; //6
            }
        }

        $user->update([
            "password" => bcrypt($p)
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
