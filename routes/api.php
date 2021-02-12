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
    Route::apiResource('service','Api\ServiceController')->except("show");

    Route::apiResource('client','Api\ClientController')->except("show");
    Route::post('client/import','Api\ClientController@import');

    Route::apiResource('user','Api\UserController');

    Route::apiResource('prestation','Api\PrestationController')->except("show");
    Route::get('prestation/date','Api\PrestationController@indexDate');
    Route::get('prestation/group-by-month','Api\PrestationController@groupByMonth');

    Route::apiResource('depense','Api\DepenseController')->except("show");
    Route::get('depense/mensuel','Api\DepenseController@depenseMensuelle');
    Route::get('depense/group-by-month','Api\DepenseController@groupByMonth');

});

Route::middleware(["auth:api", "activated"])->group(function ()
{
    Route::apiResource('salon','Api\SalonController');

    Route::get('abonnement/index','Api\AbonnementController@index');

    Route::get('recette/mois','Api\BilanController@getRecetteMois');
    Route::get('recette/salons','Api\BilanController@getRecetteSalons');
});
