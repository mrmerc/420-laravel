<?php

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
    // Test APIs
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

    // Authentication
    Route::post('auth/google/url', 'AuthController@redirectToProvider');
    Route::post('auth/google/callback', 'AuthController@handleProviderCallback');
    Route::get('auth/me', 'AuthController@me');

    // Chat
    Route::post('chat/message', 'ChatController@broadcastMessage');
    Route::get('chat/message/history/{roomId}', 'ChatController@getMessageHistory');

    // High
    Route::get('high/people', 'HighPeopleController@getHighPeople');
    Route::put('high/people', 'HighPeopleController@incrementHighPeople');

    // Articles
    Route::get('article/{articleId}', 'ArticleController@getArticleById');
    Route::post('article', 'ArticleController@submitArticle');

    // [ADMIN]
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Chat
        Route::post('chat/ban', 'ChatController@banUser');
        // Articles
        Route::post('article/approve', 'ArticleController@setArticleAvailable');
    });
});
