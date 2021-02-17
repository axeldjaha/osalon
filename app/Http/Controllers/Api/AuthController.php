<?php

namespace App\Http\Controllers\Api;

use App\Abonnement;
use App\Engin;
use App\Http\Requests\CodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\EnginResource;
use App\Http\Resources\LaveurResource;
use App\Http\Resources\ProfilResource;
use App\Http\Resources\ServiceResource;
use App\Jobs\MailJob;
use App\Jobs\SendSMS;
use App\Jobs\SMSDispatcher;
use App\Salon;
use App\Laveur;
use App\Mail\RequestCodeEmail;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use stdClass;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(["register", "login", "code", "reset"]);
    }

    public function register(RegisterRequest $request)
    {
        $salon = Salon::create([
            "nom" => $request->salon,
            "adresse" => $request->adresse,
        ]);

        $salon->update([
            "pid" => date("y") . date("m") . $salon->id,
        ]);

        Abonnement::create([
            "date" => Carbon::now(),
            "echeance" => Carbon::now()->addDays(10),
            "salon_id" => $salon->id,
        ]);

        $user = User::where("telephone", $request->telephone)->first();

        //si user n'existe pas
        if($user == null)
        {
            $password = User::generatePassword();

            $user = User::create([
                "name" => $request->name,
                "telephone" => $request->telephone,
                "email" => null,
                "password" => bcrypt($password),
            ]);

            //Envoi du mot de passe par SMS
            $message = "Votre mot de passe est: $password";
            $sms = new \stdClass();
            $sms->to = [$user->telephone];
            $sms->message = $message;
            Queue::push(new SendSMS($sms));

            $status = 201;
            $statusMessage = "Votre compte a été créé. Vous allez recevoir votre mot de passe par SMS dans quelques instants.";
        }
        else //si le user existe
        {
            //Envoi d'une notification par SMS
            $message =
                "$salon->nom a été rattaché à votre compte " . config('app.name') . "." .
                "\nVous pouvez suivre les activités de ce salon à distance partout où vous etes.";
            $sms = new \stdClass();
            $sms->to = [$user->telephone];
            $sms->message = $message;
            Queue::push(new SendSMS($sms));

            $status = 200;
            $statusMessage = "Le compte a été créé avec succès. Vous allez recevoir votre mot de passe par SMS dans quelques instants.";
        }

        $user->salons()->sync([$salon->id], false);

        $date = Carbon::now();
        $comptesDuJour = Salon::whereDate("created_at", Carbon::today())->count();
        $comptesDeLaSemaine = Salon::whereBetween("created_at", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $comptesDuMois = Salon::whereYear("created_at", $date->year)->whereMonth("created_at", $date->month)->count();
        $message = "Nouveau compte" .
            "\nSalon: $salon->nom" .
            "\nAdresse: $salon->adresse" .
            "\nAujourd'hui: $comptesDuJour" .
            "\nCette semaine: $comptesDeLaSemaine" .
            "\nCe mois: $comptesDuMois";
        $sms = new stdClass();
        $sms->to = [config("app.telephone")];
        $sms->message = $message;
        Queue::push(new SendSMS($sms));

        return response()->json([
            "message" => $statusMessage,
        ], $status);
    }

    /**
     * Get the authenticated User.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['telephone', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                "message" => "Téléphone ou mot de passe incorrect",
            ], 401);
        }

        $user = auth('api')->user();
        $user->token = $token;

        return response()->json(new AuthUserResource($user));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Send reset password code by SMS
     *
     * @param CodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function code(CodeRequest $request)
    {
        /*
         * Le code doit être unique
         * Car dans le reset le mail du user n'est pas associé au code. Et c'est fait exprès.
         */
        $string = '0123456789';
        do
        {
            $code = Str::upper(substr(str_shuffle($string), 0, 4));

        }while(DB::table("password_resets")->where(["code" => $code])->count());

        $data['code'] = $code;

        Queue::push(new MailJob($request->email, new RequestCodeEmail($data)));

        DB::table("password_resets")->where(["email" => $request->email])->delete();
        DB::table("password_resets")->insert([
            "email" => $request->email,
            "code" => $code,
            "created_at" => Carbon::now(),
        ]);

        return response()->json([
            "message" => "Un code a été envoyé sur votre email, valide 1 heure.",
        ]);
    }

    /**
     * Reset password
     *
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        /**
         * On peut renforcer la sécurité en demandant à l'utilisateur de
         * renseigner son email en plus du code qu'il a reçu.
         * Mais pour cette application, le niveau de sécurité est suffisant.
         */
        $code = DB::table("password_resets")->where(["code" => $request->code])->first();

        if(Carbon::parse($code->created_at)->addHour()->lessThan(Carbon::now()))
        {
            return response()->json([
                "message" => "Le code renseigné a expiré, veuillez demander un autre code.",
            ], 401);
        }

        $user = User::where("email", $code->email)->first();
        $user->update([
            "password" => bcrypt($request->new_password)
        ]);

        DB::table("password_resets")->where("email", $code->email)->delete();

        $credentials = [
            "telephone" => $user->telephone,
            "password" => $request->new_password,
        ];
        $token = auth('api')->attempt($credentials);
        $user->token = $token;

        return response()->json(new AuthUserResource($user));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'token' => auth("api")->refresh(),
        ]);
    }

    public function updateInfo(UpdateProfilRequest $request)
    {
        $user = \auth("api")->user();
        $user->update([
            "name" => $request->nom,
            "email" => $request->email,
            "telephone" => $request->telephone,
        ]);

        $user->token = $request->token;
        return response()->json(new AuthUserResource($user));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth("api")->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        $user->token = auth("api")->refresh();
        return response()->json(new AuthUserResource($user));
    }

    public function sync()
    {
        $user = auth("api")->user();
        $user->token = auth("api")->refresh();

        return response()->json(new AuthUserResource($user));
    }

}
