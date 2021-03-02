<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SalonResource;
use App\Http\Resources\SmsResource;
use App\Salon;
use App\Sms;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SmsController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "sms" => SmsResource::collection($salon->sms()->orderBy("id", "desc")->get()),
            ];
        }

        return response()->json($salons);
    }

    /**
     * Show prestations for given salon
     *
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( Salon $salon)
    {
        /**
         * Si au moment de l'affichage, l'utilisateur a maintenant 1 seul salon,
         * renvyer 204 pour retouner à Index et auto reactualiser
         */
        if($this->user->salons()->count() == 1)
        {
            return \response()->json(new SalonResource(new Salon()), 204);
        }

        return response()->json(SmsResource::collection($salon->sms()->orderBy("id", "desc")->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        /**
         * Rollback data that has been stored but user does not know due to connection timeout
         */
        $rollback = [];
        foreach($request->sms as $sentSms)
        {
            if($sentSms["id"] == 0)
            {
                Sms::create([
                    "message" => $sentSms["message"],
                    "recipient" => $sentSms["recipient"],
                    "date" => $sentSms["date"] ?? Carbon::now(),
                    "reference" => $sentSms["reference"] ?? Carbon::now(),
                    "user" => $sentSms["user"] ?? $this->user->name,
                    "salon_id" => $sentSms["salon_id"] ?? $this->salon->id,
                ]);
            }
            else
            {
                $rollback[] = $sentSms["reference"];
            }
        }

        $this->salon->sms()->whereIn("reference", $rollback)->delete();

        //sleep(10);

        return response()->json(new SmsResource(new Sms()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Sms $sms
     * @return Response
     */
    public function destroy($id)
    {
        /**
         * Check if the resource exists and prevent access to another user's resource
         */
        if(!$this->salon->sms()->where("id", $id)->delete())
        {
            return response()->json([
                "message" => "Le SMS n'existe pas ou a été supprimé"
            ], 404);
        }

        return response()->json(null, 204);
    }

    /**
     * Destroy all sent SMS
     *
     * @param Salon $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll(Salon $salon)
    {
        $salon->sms()->truncate();

        return response()->json(null, 204);
    }

}
