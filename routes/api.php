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
], function() {

  Route::group([
      'prefix' => 'news',
  ], function() {
        Route::post('save', 'NewsController@save');
        Route::get('get-one/{id}', 'NewsController@getOne');
        Route::post('get-list', 'NewsController@getList');
        Route::get('delete/{id}', 'NewsController@delete');
  });

  Route::group([
      'prefix' => 'news-sources',
  ], function() {
        Route::post('save', 'NewsSourcesController@save');
        Route::get('get-one/{id}', 'NewsSourcesController@getOne');
        Route::post('get-list', 'NewsSourcesController@getList');
        Route::get('delete/{id}', 'NewsSourcesController@delete');
  });

});
