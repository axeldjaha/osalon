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

Route::middleware("auth")->group(function ()
{
    /**
     * SALON
     */
    Route::group(['middleware' => ['permission:Pressings']], function () {
        Route::resource("salon", "SalonController")->only(["index", "show", "destroy"]);
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
     * SMS
     */
    Route::group(['middleware' => ['can:Envoi SMS']], function () {
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

    /**
     * USER ACCOUNT
     */
    Route::get("compte/acces", "Admin\AccountController@acces")->name("account.access");
    Route::put("compte/acces", "Admin\AccountController@updateAcces")->name("account.acces.update");
    Route::get("compte/infos", "Admin\AccountController@infos")->name("account.infos");
    Route::put("compte/infos", "Admin\AccountController@updateInfos")->name("account.infos.update");


});
