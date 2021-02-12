<?php

namespace App\Http\Controllers;

use App\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    public function index()
    {
        $data["title"] = "Offre";
        $data["active"] = "offre";

        $data["offre"] = Offre::first();

        return view("offre.index", $data);
    }

    public function edit()
    {
        $data["title"] = "Offre";
        $data["active"] = "offre";

        $data["offre"] = Offre::first();

        return view("offre.edit", $data);
    }

    public function update(Request $request, Offre $offre)
    {
        $this->validate($request, [
            "montant" => "required|numeric",
        ]);

        $offre->update(["montant" => $request->montant]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Enregistrement effectué avec succès!');

        return redirect()->route("offre.index");
    }
}
