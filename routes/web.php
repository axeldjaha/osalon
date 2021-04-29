<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

//Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', function () {
    return redirect()->route("login");
});

Auth::routes();

Route::get("/privacy", "WelcomeController@privacy");

Route::get("/test", "TestController@test");

Route::middleware(["auth", "activity"])->group(function ()
{
    /**
     * SALON
     */
    Route::get("salon", "SalonController@index")->name("salon.index");
    Route::get("salon/{salon}", "SalonController@show")
        ->where("salon", "[0-9]+")->name("salon.show");
    Route::delete("salon/{salon}", "SalonController@destroy")->name("salon.destroy");

    /**
     * COMPTES
     */
    Route::resource("compte", "CompteController");

    /**
     * ABONNEMENT
     */
    Route::get("abonnement/{compte}/create", "AbonnementController@create")->name("abonnement.create");
    Route::post("abonnement/{compte}/create", "AbonnementController@store")->name("abonnement.store");
    Route::delete("abonnement/{abonnement}", "AbonnementController@destroy")->name("abonnement.destroy");

    /**
     * RECHARGE SMS
     */
    Route::get("recharge/{compte}/create", "RechareSMSController@create")->name("recharge.create");
    Route::post("recharge/{compte}/create", "RechareSMSController@store")->name("recharge.store");

    /**
     * USERS
     */
    Route::get("user", "UserController@index")->name("user.index");
    Route::put("user/password/reset/{user}/", "UserController@resetPassword")->name("user.password.reset");

    /**
     * OFFRES
     */
    Route::resource("offre", "OffreController")->only(["index", "edit", "update"]);

    /**
     * OFFRES SMS
     */
    Route::get("offres/sms", "OffreSmsController@index")->name("offre.sms.index");
    Route::get("offres/sms/create", "OffreSmsController@create")->name("offre.sms.create");
    Route::post("offres/sms/create", "OffreSmsController@store")->name("offre.sms.store");
    Route::get("offres/sms/{offreSms}/edit", "OffreSmsController@edit")->name("offre.sms.edit");
    Route::put("offres/sms/{offreSms}/edit", "OffreSmsController@update")->name("offre.sms.update");
    Route::delete("offres/sms/{offreSms}", "OffreSmsController@destroy")->name("offre.sms.destroy");

    /**
     * SMS
     */
    Route::get("/sms", "SMSController@index")->name("sms.index");
    Route::get("/sms/envoi", "SMSController@create")->name("sms.create");
    Route::post("/sms/envoi", "SMSController@store")->name("sms.envoi");
    Route::delete("sms", "SMSController@destroy")->name("sms.destroy");
    Route::get("sms/fichier", "SmsGroupeController@index")->name("sms.fichier.index");
    Route::get("sms/fichier/{sms_groupe}", "SmsGroupeController@show")->name("sms.fichier.show");
    Route::post("sms/fichier/importer", "SmsGroupeController@importer")->name("sms.fichier.importer");
    Route::get("sms/fichier/{sms_groupe}/exporter", "SmsGroupeController@exporter")->name("sms.fichier.exporter");
    Route::delete("sms/fichier/{sms_groupe}", "SmsGroupeController@destroy")->name("sms.fichier.destroy");
    Route::post("sms/contact/store", "SmsGroupeController@storeContact")->name("sms.contact.store");
    Route::delete("sms/contact/{contact}", "SmsGroupeController@destroyContact")->name("sms.contact.destroy");

    /**
     * DASHBOARD
     */
    Route::get("/home", "HomeController@index")->name("dashboard");

    /**
     * ADMIN
     */
    Route::group(['middleware' => ['permission:Admins']], function () {
        Route::resource("/admin", "Admin\AdminController")->except("show");
    });

    /**
     * USER ACCOUNT
     */
    Route::get("profile/acces", "Admin\AccountController@acces")->name("account.access");
    Route::put("profile/acces", "Admin\AccountController@updateAcces")->name("account.acces.update");
    Route::get("profile/infos", "Admin\AccountController@infos")->name("account.infos");
    Route::put("profile/infos", "Admin\AccountController@updateInfos")->name("account.infos.update");
});

Route::group(['prefix' => 'activity', 'namespace' => '\\jeremykenedy\LaravelLogger\App\Http\Controllers', 'middleware' => ['auth']], function () {

    // Dashboards
    Route::get('/', 'LaravelLoggerController@showAccessLog')->name('activity');
    Route::get('/cleared', ['uses' => 'LaravelLoggerController@showClearedActivityLog'])->name('cleared');

    // Drill Downs
    Route::get('/log/{id}', 'LaravelLoggerController@showAccessLogEntry');
    Route::get('/cleared/log/{id}', 'LaravelLoggerController@showClearedAccessLogEntry');

    // Forms
    Route::delete('/clear-activity', ['uses' => 'LaravelLoggerController@clearActivityLog'])->name('clear-activity');
    Route::delete('/destroy-activity', ['uses' => 'LaravelLoggerController@destroyActivityLog'])->name('destroy-activity');
    Route::post('/restore-log', ['uses' => 'LaravelLoggerController@restoreClearedActivityLog'])->name('restore-activity');
});

