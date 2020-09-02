<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'api/v1.0', 'namespace' => 'Gluon\Backend\Controllers'], function()
{
  Route::get('/', function() {return redirect('/app');});
  Route::get('/login', "UserController@index");
  Route::post('/login', "UserController@login");
  Route::post('/register', "UserController@register");
  
  Route::get('/schema', 'SchemaController@index');
});


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

Route::group(['prefix' => 'api/v1.0', 'namespace' => 'Gluon\Backend\Controllers', 'middleware' => ['auth:gluon']], function() {
    Route::get('/schema', 'SchemaController@schema');

    Route::get('/logout', "UserController@logout");
    Route::get('/checkLogin', "UserController@checkLogin");

    Route::get('/{type}', 'APIController@readAll');
    Route::get('/{type}/{id}/{reference}', 'APIController@readReference');
    Route::post('/{type}/query', 'APIController@query');
    Route::get('/{type}/{id}', 'APIController@read');
    Route::post('/{type}', 'APIController@create');
    Route::put('/{type}', 'APIController@bulkUpdate');
    Route::put('/{type}/{id}', 'APIController@update');
    Route::delete('/{type}/query', 'APIController@deleteQuery');
    Route::delete('/{type}/{id}', 'APIController@delete');

    Route::get('/{type}/{id}/log', 'VersionController@log');
    Route::put('/{type}/restore/{log}', 'VersionController@restore');

    Route::get('/{type}/{id}/documents', 'DocumentController@list');
    Route::post('/{type}/{id}/documents', 'DocumentController@upload');
    Route::delete('/{type}/{id}/documents/{ids}', 'DocumentController@delete');

    Route::get('/notifications', 'NotificationController@index');
    Route::delete('/notifications', 'NotificationController@clear');
});


