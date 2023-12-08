<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->namespace('App\Http\Controllers\API\V1')->group(function () {
    Route::post('login', 'AuthenticationController@login')->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'AuthenticationController@logout')->name('auth.logout');
        Route::post('me', 'AuthenticationController@me')->name('auth.me');
    });
});
