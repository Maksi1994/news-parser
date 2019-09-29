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

Route::group([
    'prefix' => 'backend',
    'namespace' => 'Backend'
], function () {

    Route::group([
        'prefix' => 'news',
    ], function () {
        Route::post('save', 'NewsController@save');
        Route::get('get-one/{id}', 'NewsController@getOne');
        Route::post('get-list', 'NewsController@getList');
        Route::get('delete/{id}', 'NewsController@delete');
    });

    Route::group([
        'prefix' => 'news-sources',
    ], function () {
        Route::post('save', 'NewsSourcesController@save');
        Route::get('get-one/{id}', 'NewsSourcesController@getOne');
        Route::post('get-list', 'NewsSourcesController@getList');
        Route::get('delete/{id}', 'NewsSourcesController@delete');
    });

    Route::group([
        'prefix' => 'likes',
    ], function () {
        Route::post('save-type', 'LikesController@saveType');
        Route::get('get-all-types', 'LikesController@getAllTypes');
        Route::get('delete/{id}', 'LikesController@delete');
    });

});

Route::group([
    'prefix' => 'comments'
], function () {
    Route::post('save', 'CommentsController@save');
    Route::post('get-list', 'CommentsController@getList');
    Route::get('delete/{id}', 'CommentsController@delete');
});

Route::group([
    'prefix' => 'favorites'
], function () {
    Route::post('toggle', 'FavoritesController@toggle');
    Route::post('get-list', 'FavoritesController@getList');
    Route::get('delete/{id}', 'FavoritesController@delete');
});

Route::group([
    'prefix' => 'news'
], function () {
    Route::get('get-one/{id}', 'NewsController@getOne');
    Route::post('get-list', 'NewsController@getList');
});

Route::group([
    'prefix' => 'likes'
], function () {
    Route::post('toggle', 'LikesController@toggle');
    Route::post('get-all', 'LikesController@getAll');
});



