<?php

namespace App\Http\Controllers\Api;

use App\Fakedata;
use App\Http\Requests\SmsRequest;
use App\Http\Resources\SalonResource;
use App\Http\Resources\SmsResource;
use App\Jobs\BulkSMS;
use App\Lien;
use App\Salon;
use App\Sms;
use App\SMSCounter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SmsController extends ApiController
{

    public function getBalance()
    {
        $salons = [];
        foreach ($this->user->salons()->orderBy("nom")->get() as $salon)
        {
            $salons[] = [
                "id" => $salon->id,
                "nom" => $salon->nom,
                "adresse" => $salon->adresse,
                "sms_balance" => $salon->sms,
            ];
        }

        return response()->json($salons);
    }

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
         * renvoyer 204 pour retouner à Index et auto reactualiser
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
     * @param SmsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /*$data = json_encode($request->json()->all());
        Fakedata::create(["data" => $request->message]);
        Fakedata::create(["data" => count($request->to)]);
        return response()->json(["message" => "super!"], 400);*/

        $to = $this->salon->clients()
            ->whereIn("id", $request->to)
            ->pluck("telephone")
            ->toArray();

        if(count($to) == 0)
        {
            return response()->json([
                "message" => "Aucun client n'a été sélectionné."
            ], 422);
        }

        /**
         * Images link length
         */
        $linkLength = $request->files->count() > 0 ? 4 : 0;

        /**
         * Le lien se termine par 5 caractères alphanumériques y compris le slash
         */
        $imagesLink = config("app.url") . "/";
        for ($index = 0; $index < $linkLength; $index++)
        {
            $imagesLink .= $index + 1;
        }

        $message = str_replace("\r\n", "\n", trim($request->message . "\n" . $imagesLink));
        $smsCounter = new SMSCounter();
        $smsInfo = $smsCounter->count($message);
        $volume = $smsInfo->messages * count($to);

        if($volume <= $this->salon->sms)
        {
            $this->salon->decrement("sms", $volume);

            $sms = Sms::create([
                "message" => $request->message,
                "recipient" => count($request->to),
                "date" => Carbon::now(),
                "user" => $this->user->name,
                "salon_id" => $this->salon->id,
            ]);

            if($request->files->count())
            {
                $destinationPath = public_path() ."/files";

                if (!file_exists($destinationPath)) {
                    File::makeDirectory($destinationPath); //creates directory
                }

                /**
                 * Création du lien
                 */
                do
                {
                    $url = Str::random($linkLength);

                }while(Lien::where('url', $url)->count());
                $lien = Lien::create(['url' => $url, "sms_id" => $sms->id]);

                /**
                 * Enregistrement des images
                 */
                Fakedata::create(["data" => "file: " . $request->files->count()]);
                foreach ($request->files as $file)
                {
                    $image = \App\Image::create([
                        "lien_id" => $lien->id,
                    ]);

                    $filename = md5($image->id) . '.' . $file->getClientOriginalExtension();
                    $img = Image::make($file->getRealPath());
                    $img->save("$destinationPath/$filename");

                    $image->update(["nom" => $filename]);
                    Fakedata::create(["data" => $filename]);
                }
            }

            //todo Queue::push(new BulkSMS($message, $to, config("app.sms_client_sender")));
        }
        else
        {
            if(isset($lien))
            {
                $lien->delete();
            }

            return response()->json([
                "message" => "Crédit SMS insuffisant. Veuillez recharger votre compte."
            ], 402);
        }

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
