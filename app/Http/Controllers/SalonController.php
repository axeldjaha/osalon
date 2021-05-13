<?php

namespace App\Http\Controllers;

use App\Pays;
use App\Salon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data["title"] = "Salons";
        $data["active"] = "salon";

        $data["salons"] = Salon::orderBy("nom", 'asc')->get();

        return view("salon.index", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pays  $salon
     * @return \Illuminate\Http\Response
     */
    public function edit(Salon $salon)
    {
        $data["title"] = "Salons";
        $data["active"] = "salon";

        $data["salon"] = $salon;
        $data["countries"] = Pays::orderBy("nom")->get();

        return view("salon.edit", $data);
    }

    public function update(Request $request, Salon $salon)
    {
        $this->validate($request, [
            "nom" => "required",
            "adresse" => "required",
            "telephone" => "required",
            "pays" => "required|exists:pays,id",
        ]);

        $salon->update([
            "nom" => $request->nom,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "pays_id" => $request->pays,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Mise à jour effectuée avec succès!');

        return redirect()->route("salon.index");
    }

    /**
     * Display the specified resource.
     *
     * @param Salon $salon
     * @return Response
     */
    public function show(Salon $salon)
    {
        $data["title"] = "Salon";
        $data["active"] = "salon";

        $data["salon"] = $salon;

        return view("salon.show", $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Salon $salon
     * @return Response
     * @throws Exception
     */
    public function destroy(Salon $salon)
    {
        if($salon->compte->salons()->count() == 1)
        {
            session()->flash('type', 'alert-danger');
            session()->flash('message', 'Vous ne pouvez pas supprimer le seul salon de ce compte');
            return back();
        }

        $salon->users()->each(function ($user)
        {
            if($user->salons()->count() == 1)
            {
                $user->delete();
            }
        });

        $salon->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("salon.index");
    }
}
