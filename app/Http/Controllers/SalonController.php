<?php

namespace App\Http\Controllers;

use App\Salon;

class SalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["title"] = "Pressing";
        $data["active"] = "salon";

        $data["salons"] = Salon::orderBy("id", 'desc')->get();

        return view("salon.index", $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salon  $salon
     * @return \Illuminate\Http\Response
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
     * @param \App\Salon $salon
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Salon $salon)
    {
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
