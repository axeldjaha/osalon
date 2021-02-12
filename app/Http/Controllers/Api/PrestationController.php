<?php

namespace App\Http\Controllers\Api;

use App\Fakedata;
use App\Http\Requests\IndexPrestationRequest;
use App\Http\Requests\PrestationRequest;
use App\Http\Resources\DepenseResource;
use App\Http\Resources\PrestationResource;
use App\Prestation;
use App\Recette;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PrestationController extends ApiController
{
    /**
     * Liste des prestations selon date
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDate(IndexPrestationRequest $request)
    {
        $prestations = $this->salon->prestations()
            ->whereDate('created_at', $request->date ?? Carbon::now())
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json(PrestationResource::collection($prestations));
    }

    /**
     * Liste des prestations selon mois
     *
     * @param IndexPrestationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexPrestationRequest $request)
    {
        $prestations = $this->salon->prestations()
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', $request->mois ?? Carbon::now()->month)
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json(PrestationResource::collection($prestations));
    }

    /**
     * Liste des recettes groupées par mois
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupByMonth()
    {
        $depenses = $this->salon->prestations()
            ->whereYear('created_at', Carbon::now()->year)
            ->select([DB::raw("MONTH(created_at) as mois"), DB::raw("(SUM(total)) as total"),])
            ->orderBy('created_at', "DESC")
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->get();
        return response()->json(PrestationResource::collection($depenses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PrestationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PrestationRequest $request)
    {
        //$data = json_encode($request->json()->all());
        //Fakedata::create(["data" => $data]);
        //return response()->json(["message" => "super!"], 400);

        $prestation = Prestation::create([
            "total" => $request->total,
            "salon_id" => $request->salon,
        ]);

        foreach ($request->services as $service)
        {
            $prestation->services()->sync([$service["id"]], false);
        }

        return response()->json(new DepenseResource($prestation));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Prestation $prestation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestation $prestation)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->prestations()->where("id", $prestation->id)->delete())
        {
            return response()->json([
                "message" => "La prestation n'existe pas ou a été supprimée."
            ], 404);
        }

        return response()->json(null, 204);
    }
}
