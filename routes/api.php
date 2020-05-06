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

Route::prefix('v1')->group(function() {
    Route::get('authtest', function () {
        return response()->json([
            'message' => 'test from auth protected api'
        ]);
    })->middleware(['auth:api']);

    Route::get('test', function () {
        return response()->json([
            'message' => 'test from base api',
            'ip' => request()->ip()
        ]);
    });

    Route::post('login/google', 'AuthController@handleProviderCallback')
        ->name('login');

    Route::get('user/self', 'AuthController@me');

    Route::post('chat/message', 'ChatController@broadcastMessage');
    Route::get('chat/message/history/{roomId}', 'ChatController@getMessageHistory');
});
