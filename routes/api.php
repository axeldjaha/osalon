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
 * Main routes
 */
Route::middleware(["auth:api", "activated", "salon", "abonnement"])->group(function ()
{
    Route::apiResource('service','Api\ServiceController')->only(["store", "update", "delete"]);

    Route::apiResource('client','Api\ClientController')->only(["store", "update", "delete"]);
    Route::post('client/import','Api\ClientController@import');

    Route::apiResource('user','Api\UserController')->only(["store", "update", "delete"]);

    Route::apiResource('depense','Api\DepenseController')->only(["store", "update", "delete"]);

    Route::get('sms/client/get','Api\ClientController@get');

    Route::apiResource('prestation','Api\PrestationController')->except("show");
    Route::get('prestation/date','Api\PrestationController@indexDate');
    Route::get('prestation/group-by-month','Api\PrestationController@groupByMonth');
});

/**
 * Since resources must be created/updated before we can access them,
 * it's not necessary to add abonnement middleware for the routes below
 */
Route::middleware(["auth:api", "activated"])->group(function ()
{
    Route::apiResource('salon','Api\SalonController');

    Route::get('user','Api\UserController@index');
    Route::get('user/salon/{salon}','Api\UserController@show');

    Route::get('client','Api\ClientController@index');
    Route::get('client/salon/{salon}','Api\ClientController@show');

    Route::get('service','Api\ServiceController@index');
    Route::get('service/salon/{salon}','Api\ServiceController@show');

    Route::get('depense','Api\DepenseController@index');
    Route::get('depense/salon/{salon}','Api\DepenseController@show');

    Route::get('recette/mois','Api\BilanController@getRecetteMois');
    Route::get('recette/salons','Api\BilanController@getRecetteSalons');

    Route::get('abonnement/index','Api\AbonnementController@index');

});
