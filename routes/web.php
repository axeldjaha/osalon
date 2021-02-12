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

Route::get("/cgu", "WelcomeController@cgu");

Route::get("/test", "TestController@test");

Route::middleware("auth")->group(function ()
{
    /**
     * PRESSING
     */
    Route::group(['middleware' => ['permission:Pressings']], function () {
        Route::resource("pressing", "PressingController");
        Route::get("pressing/{pressing}/users", "PressingController@users")->name("pressing.users");
        Route::get("pressing/{pressing}/user/create", "PressingController@createUser")->name("pressing.createUser");
        Route::post("pressing/{pressing}/user", "PressingController@storeUser")->name("pressing.storeUser");
        Route::delete("pressing/{pressing}/user/{user}", "PressingController@destroyeUser")->name("pressing.destroyeUser");
        Route::resource("pressing/{pressing}/service", "ServiceController");
    });

    /**
     * USERS
     */
    Route::group(['middleware' => ['permission:Users']], function () {
        Route::resource("user", "UserController");
        Route::get("user/{user}/acces", "UserController@acces")->name("user.acces");
        Route::put("user/{user}/lock", "UserController@lock")->name("user.lock");
        Route::put("user/{user}/unlock", "UserController@unlock")->name("user.unlock");
    });

    /**
     * ABONNEMENT
     */
    Route::group(['middleware' => ['permission:Abonnements']], function () {
        Route::resource("abonnement", "AbonnementController")->only(["index", "create", "store"]);
        Route::get("abonnement/pressing/{pressing}/", "AbonnementController@reabonnement")->name("abonnement.reabonnement");
        Route::post("abonnement/pressing/{pressing}/", "AbonnementController@reabonner")->name("abonnement.reabonner");
    });

    /**
     * TRANSACTIONS
     */
    Route::group(['middleware' => ['permission:Transactions']], function () {
        Route::get("transaction", "TransactionController@index")->name("transaction.index");
    });

    /**
     * OFFRES
     */
    Route::group(['middleware' => ['permission:Offres']], function () {
        Route::resource("offre", "OffreController")->only(["index", "edit", "update"]);
    });

    /**
     * FICHIERS DE PROSPECTS
     */
    Route::group(['middleware' => ['permission:Prospects']], function () {
        Route::resource("prospect/fichier", "FichierProspectController");
        Route::post("prospect/fichier/{fichierProspect}}", "ProspectController@store")->name("fichier.prospect.store");
        Route::delete("prospects/fichier/{fichierProspect}/prospect/{prospect}", "ProspectController@destroy")->name("fichier.prospect.destroy");
    });

    /**
     * SMS
     */
    Route::group(['middleware' => ['permission:SMS']], function () {
        Route::get("/sms", "SMSController@index")->name("sms.index");
        Route::get("/sms/envoi", "SMSController@create")->name("sms.create");
        Route::post("/sms/envoi", "SMSController@store")->name("sms.store");
        Route::delete("sms/{id}", "SMSController@destroy")->name("sms.destroy");
        Route::delete("sms/delete/checked", "SMSController@destroyChecked")->name("sms.delete.checked");
        Route::get("sms/recipients/prospects", "SMSController@loadProspects")->name("sms.recipients.prospects");
        Route::get("sms/recipients/clients", "SMSController@loadClients")->name("sms.recipients.clients");
    });


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
    // profile
    Route::get("/compte/mes-acces", "Admin\ProfilController@acces")->name("profil.acces");
    Route::put("/compte/mes-acces", "Admin\ProfilController@updateAcces")->name("profil.acces");
    Route::get("/compte/mes-infos", "Admin\ProfilController@infos")->name("profil.infos");
    Route::put("/compte/mes-infos", "Admin\ProfilController@updateInfos")->name("profil.infos");


});
