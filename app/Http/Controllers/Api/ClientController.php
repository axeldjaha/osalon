<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Fakedata;
use App\Http\Requests\ClientImport;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ClientController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $clients = $this->salon->clients()->orderBy("nom")->get();

        return response()->json(ClientResource::collection($clients));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientRequest $request
     * @return Response
     */
    public function store(ClientRequest $request)
    {
        $client = Client::create([
            "nom" => $request->nom,
            "telephone" => $request->telephone,
            "anniversaire" => $request->anniversaire, //1970-04-24
            "salon_id" => $request->salon,
        ]);

        return response()->json(new ClientResource($client));
    }

    /**
     * Import client
     *
     * @param ClientRequest $request
     * @return Response
     */
    public function import(ClientImport $request)
    {
        //$data = json_encode($request->json()->all());
        //Fakedata::create(["data" => $data]);
        //return response()->json(["message" => "super!"], 400);

        $clients = [];
        $date = Carbon::now();

        foreach ($request->all() as $client)
        {
            if(isset($client["nom"]) && isset($client["telephone"]))
            {
                $telephone = str_replace("+225", "", $client["telephone"]);
                $clients[] = [
                    $client["nom"],
                    $telephone,
                    $client["anniversaire"] ?? null,
                    $this->salon->id,
                    $date,
                    $date,
                ];
            }
        }

        if(count($clients) > 0)
        {
            $columns = [
                "nom",
                "telephone",
                "anniversaire",
                "salon_id",
                "created_at",
                "updated_at",
            ];
            $model = new Client();
            batch()->insert($model, $columns, $clients);
        }

        return response()->json();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientRequest $request
     * @param Client $client
     * @return Response
     * @throws \Exception
     */
    public function update(ClientRequest $request, Client $client)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->clients()->where("id", $client->id)->update([
            "nom" => $request->nom,
            "telephone" => $request->telephone,
            "anniversaire" => $request->anniversaire,
        ]))
        {
            return response()->json([
                "message" => "Le client n'existe pas ou a été supprimé"
            ], 404);
        }

        $client = $client->refresh();

        return response()->json(new ClientResource($client));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return Response
     */
    public function destroy(Client $client)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->clients()->where("id", $client->id)->delete())
        {
            return response()->json([
                "message" => "Le client n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }
}
