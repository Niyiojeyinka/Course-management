<?php

use Illuminate\Http\Request;

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
//public route
Route::prefix('v1/user')
    ->middleware([])
    ->group(function () {
        Route::post('register', 'UserAuthController@create');
        Route::post('logout', 'UserAuthController@logout');
        Route::post('login', 'UserAuthController@login');
    });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});