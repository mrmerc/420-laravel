<?php

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
    Route::get('authtest', 'TestController@authTest')->middleware(['auth:api']);
    Route::get('test', 'TestController@test');

    // Authentication
    Route::get('auth/{provider}/url', 'AuthController@redirectToProvider');
    Route::post('auth/{provider}/callback', 'AuthController@handleProviderCallback');
    Route::post('auth/token/refresh', 'AuthController@refresh');

    // Chat
    Route::post('chat/message', 'ChatController@broadcastMessage');
    Route::get('chat/message/history/{room_id}', 'ChatController@getMessageHistory');

    // High
    Route::get('high/people', 'HighPeopleController@getHighPeople');
    Route::put('high/people', 'HighPeopleController@incrementHighPeople');

    // Articles
    Route::get('article/{article_id}', 'ArticleController@getArticleById');
    Route::post('article', 'ArticleController@submitArticle');

    // [ADMIN]
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Chat
        Route::post('chat/ban', 'ChatController@banUser');
        // Articles
        Route::post('article/approve', 'ArticleController@setArticleAvailable');
    });
});
