<?php

namespace App\Http\Controllers;

use App\Salon;
use Exception;
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
