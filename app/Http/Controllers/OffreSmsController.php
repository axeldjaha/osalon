<?php

namespace App\Http\Controllers;

use App\OffreSms;
use Illuminate\Http\Request;

class OffreSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "Offres SMS";
        $data["active"] = "offresms";

        $data["offres"] = OffreSms::orderBy("quantite")->get();

        return view("offresms.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["title"] = "Offres SMS";
        $data["active"] = "offresms";

        return view("offresms.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "quantite" => "required|numeric",
            "prix" => "required|numeric",
        ]);

        OffreSms::create([
            "quantite" => $request->quantite,
            "prix" => $request->prix,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Enregistrement effectué avec succès!');

        return redirect()->route("offre.sms.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OffreSms  $offreSms
     * @return \Illuminate\Http\Response
     */
    public function edit(OffreSms $offreSms)
    {
        $data["title"] = "Offres SMS";
        $data["active"] = "offresms";

        $data["offre"] = $offreSms;

        return view("offresms.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OffreSms  $offreSms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OffreSms $offreSms)
    {
        $this->validate($request, [
            "quantite" => "required|numeric",
            "prix" => "required|numeric",
        ]);

        $offreSms->update([
            "quantite" => $request->quantite,
            "prix" => $request->prix,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Mise à jour effectuée avec succès!');

        return redirect()->route("offre.sms.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OffreSms  $offreSms
     * @return \Illuminate\Http\Response
     */
    public function destroy(OffreSms $offreSms)
    {
        $offreSms->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return redirect()->route("offre.sms.index");
    }

}
