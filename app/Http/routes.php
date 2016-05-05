<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/dashboard', 'DashboardController@index');

Route::get('/viewChest/{id}','ChestController@index');

Route::get('/viewChest/{id}/add','ChestController@addFile');

Route::get('/newChest','ChestController@newChest');


Route::get('/delete/file/{id}','FileController@delete');

Route::get('/delete/chest/{id}','ChestController@delete');

Route::post('/create','ChestController@create');

Route::post('/upload/{id}','FileController@upload');

Route::get('/twitter', function()
{
    return Twitter::linkify('https://twitter.com/ArchonAmazHS/status/727510623131594753');
});