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
        Route::get('is-unique-domain/{url}', 'NewsSourcesController@isUniqueDomain');
        Route::get('is-unique-name/{name}', 'NewsSourcesController@isUniqueName');
        Route::get('delete/{id}', 'NewsSourcesController@delete');
    });

    Route::group([
        'prefix' => 'likes',
    ], function () {
        Route::post('save-type', 'LikesController@saveType');
        Route::get('get-all-types', 'LikesController@getAllTypes');
        Route::get('delete/{id}', 'LikesController@delete');
    });

    Route::group([
        'prefix' => 'roles',
    ], function () {
        Route::get('get-list', 'RolesController@getList');
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
    Route::get('get-popular', 'NewsController@getPopular');
    Route::post('get-list', 'NewsController@getList');
    Route::get('dispatch-job', 'NewsController@dispatchJob');
    Route::get('get-filters/{id?}', 'NewsController@getFilters');
});


Route::group([
    'prefix' => 'users'
], function () {
    Route::get('is-unique-email/{email}', 'UsersController@isUniqueEmail');
    Route::post('regist', 'UsersController@regist');
    Route::get('accept-registration/{token}', 'UsersController@acceptRegistration');
    Route::post('login', 'UsersController@login');
    Route::get('logout', 'UsersController@logout')->middleware('auth:api');
    Route::get('get-curr-user', 'UsersController@getCurrUser')->middleware('auth:api');
});

Route::group([
    'prefix' => 'likes'
], function () {
    Route::post('toggle', 'LikesController@toggle');
    Route::post('get-all', 'LikesController@getAll');
});
