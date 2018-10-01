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

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('signup/{role}', 'AuthController@register')
        ->where('role', 'owner|user|admin');
    Route::post('login', 'AuthController@login');
    Route::get('me', 'AuthController@me')
        ->middleware('auth:api');
    Route::post('logout', 'AuthController@logout')
        ->middleware('auth:api');
    Route::post('refresh', 'AuthController@refresh');
});

Route::middleware('jwt.auth')->group(function(){

   
    Route::resource('user', 'UserController')
        ->only(['show', 'update']);


    Route::group([
        'prefix' => 'file'
    ], function () {
        Route::post('listOnBase', 'FileController@index');
        Route::post('listOnServer', 'FileController@listOnServer');
        Route::post('store', 'FileController@store');
        Route::delete('destroy/{file}', 'FileController@destroy');
        Route::get('download/{file}', 'FileController@download');
        Route::get('getURL/{file}', 'FileController@getURL');
    });
});