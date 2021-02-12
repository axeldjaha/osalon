<?php

namespace App\Http\Controllers;

use App\Salon;
use App\Engin;
use App\Prestation;
use App\Service;
use App\Tarification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Salon $salon)
    {
        $data["title"] = "Service";
        $data["active"] = "salon";
        $data["tab"] = "services";

        $data["salon"] = $salon;
        $data["services"] = $salon->services()->orderBy("nom")->get();

        return view("salon.service.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Salon $salon)
    {
        $data["title"] = "Créer service";
        $data["active"] = "salon";
        $data["tab"] = "services";

        $data["salon"] = $salon;

        return view("salon.service.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Salon $salon
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Salon $salon, Request $request)
    {
        $this->validate($request, [
            "nom" => "required",
        ]);

        Service::create([
            "nom" => $request->nom,
            "salon_id" => $salon->id,
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Service créé avec succès!');

        return redirect()->route("service.index", $salon);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Salon $salon
     * @param \App\Engin $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Salon $salon, Service $service)
    {
        $data["title"] = "Modifier service";
        $data["active"] = "salon";
        $data["tab"] = "services";

        $data["salon"] = $salon;
        $data["service"] = $service;

        return view("salon.service.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Salon $salon
     * @param \App\Engin $service
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Salon $salon, Service $service, Request $request)
    {
        $this->validate($request, [
            "nom" => "required",
        ]);

        $service->update([
            "nom" => $request->nom
        ]);

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Modification effectuée avec succès!');

        return redirect()->route("service.index", $salon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Engin $service
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Salon $salon, Service $service)
    {
        $salon->services()->where("id", $service->id)->delete();

        session()->flash('type', 'alert-success');
        session()->flash('message', 'Suppression effectuée avec succès!');

        return back();
    }

}
