<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\RdvRequest;
use App\Http\Resources\RdvResource;
use App\Http\Resources\SalonResource;
use App\Rdv;
use App\Salon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RdvController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = "
        SELECT date,
          COUNT(*) AS total
        FROM rdvs
        WHERE salon_id = ? AND date >= ?
        GROUP BY date
        ORDER BY date";
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $rdvData = [];
            $rdvs = DB::select($query, [$salon->id, Carbon::today()]);
            foreach ($rdvs as $rdv)
            {
                $rdvData[] = [
                    "date" => $rdv->date,
                    "date_iso_format" => ucfirst(Carbon::parse($rdv->date)->locale("fr_FR")->isoFormat('ddd DD MMMM')),
                    "total" => $rdv->total,
                    "salon_id" => $salon->id,
                ];
            }

            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "rdvs" => $rdvData,
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show prestations for given salon
     *
     * @param Request $request
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Salon $salon)
    {
        $rdvs = $salon->rdvs()
            ->where("date", "=", $request->date)
            ->orderBy("heure")
            ->get();

        return response()->json(RdvResource::collection($rdvs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RdvRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RdvRequest $request)
    {
        $rdv = Rdv::create([
            "date" => $request->date,
            "heure" => $request->heure,
            "nom" => $request->nom,
            "telephone" => $request->telephone,
            "salon_id" => $request->salon,
        ]);

        return response()->json(new RdvResource($rdv));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RdvRequest $request
     * @param Rdv $rdv
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RdvRequest $request, Rdv $rdv)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->rdvs()->where("id", $rdv->id)->update([
            "date" => $request->date,
            "heure" => $request->heure,
            "nom" => $request->nom,
            "telephone" => $request->telephone,
        ]))
        {
            return response()->json([
                "message" => "Le RDV n'existe pas ou a été supprimé"
            ], 404);
        }

        $rdv = $rdv->fresh();

        return response()->json(new RdvResource($rdv));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rdv $rdv
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Rdv $rdv)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->rdvs()->where("id", $rdv->id)->delete())
        {
            return response()->json([
                "message" => "Le RDV n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
