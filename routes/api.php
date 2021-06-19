<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', 'Api\AuthController@register');

Route::post('/login', 'Api\AuthController@login');

Route::get('/logout', 'Api\AuthController@logout');

Route::get('/password/code', 'Api\AuthController@code');

Route::post('/password/reset', 'Api\AuthController@reset');

Route::put('/user/info', 'Api\AuthController@updateInfo');

Route::put('/user/password', 'Api\AuthController@updatePassword');

Route::get('user/sync','Api\AuthController@sync');

/**
 * Main routes.
 */
Route::middleware(["auth:api", "salon", "abonnement", "activity"])->group(function ()
{
    Route::apiResource('article','Api\ArticleController')->except(["index", "show"]);

    Route::apiResource('client','Api\ClientController')->except(["index", "show"]);
    Route::post('client/import','Api\ClientController@import');

    Route::apiResource('user','Api\UserController')->except(["index", "show"]);

    Route::apiResource('depense','Api\DepenseController')->except(["index", "show"]);

    Route::apiResource('panier','Api\PanierController')->except(["index", "show"]);
    Route::delete('panier/{panier}/article/{article}','Api\PanierController@deleteArticle');

    Route::apiResource('rdv','Api\RdvController')->except(["index", "show"]);

    Route::delete('sms/{sms}','Api\SmsController@destroy');
    Route::delete('sms/all/destroy','Api\SmsController@destroyAll');
});

Route::middleware(["auth:api", "abonnement", "activity"])->group(function ()
{
    Route::apiResource('salon','Api\SalonController')->except(["index"]);

    Route::get('bilan','Api\BilanController@bilan');
    Route::get('bilan/point','Api\BilanController@point');
    Route::get('bilan/detail','Api\BilanController@detail');

    Route::post('sms','Api\SmsController@store');

    Route::get('permission','Api\PermissionController@index');
});

/**
 * Since resources must be created/updated before we can access them,
 * it's not necessary to add abonnement middleware for the routes below
 */
Route::middleware(["auth:api", "activity"])->group(function ()
{
    Route::get('salon','Api\SalonController@index');

    Route::get('user','Api\UserController@index');
    Route::get('user/salon/{salon}','Api\UserController@show');

    Route::get('client','Api\ClientController@index');
    Route::get('client/salon/{salon}','Api\ClientController@show');

    Route::get('article','Api\ArticleController@index');
    Route::get('article/salon/{salon}','Api\ArticleController@show');

    Route::get('depense','Api\DepenseController@index');
    Route::get('depense/salon/{salon}','Api\DepenseController@show');

    Route::get('panier','Api\PanierController@index');
    Route::get('panier/salon/{salon}','Api\PanierController@show');

    Route::get('sms','Api\SmsController@index');
    Route::get('sms/salon/{salon}','Api\SmsController@show');

    Route::get('rdv','Api\RdvController@index')->name("rdv.index");
    Route::get('rdv/salon/{salon}','Api\RdvController@show');

    Route::get('abonnement/index','Api\AbonnementController@index');

    Route::get('sms/offres','Api\OffreSMSController@index');

});
