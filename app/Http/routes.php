<?php
use Illuminate\Support\Facades\Input;
use App\DatabaseInteraction;

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

Route::get('/soundcloudLogin/{id}', 'SoundcloudController@register');
Route::get('/SCcallback', 'SoundcloudController@callback');


Route::get('/youtubeLogin/{id}', ['uses' => 'GoogleLoginController@index', 'as' => 'login']);
Route::get('/loginCallback', ['uses' => 'GoogleLoginController@store', 'as' => 'loginCallback']);

Route::get('/tweetFile/{id}', 'TwitterController@tweetFile');
Route::get('/tweet/{id}', ['as' => 'twitter.login', 'uses' => 'TwitterController@tweet']);
Route::get('/twitter/callback', ['as' => 'twitter.callback', 'uses' => 'TwitterController@callback']);
Route::get('/twitter/logout', ['as' => 'twitter.logout', 'uses' => 'TwitterController@logout']);


Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');


Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/search', 'DashboardController@search');


Route::get('/newChest', 'ChestController@newChest');
Route::get('/viewChest/{id}', 'ChestController@index');
Route::get('/viewChest/{id}/search', 'ChestController@search');
Route::get('/delete/chest/{id}', 'ChestController@delete');
Route::get('/edit/chest/{id}', 'ChestController@edit');
Route::patch('/update/chest/{id}', 'ChestController@update');
Route::post('/create', 'ChestController@create');

Route::get('/viewChest/{id}/add', 'AddController@addFile');
Route::get('/viewChest/{id}/add/local', 'AddController@addLocalFile');
Route::get('/viewChest/{id}/add/facebook', 'AddController@addFacebookFile');
Route::get('/viewChest/{id}/add/youtube', 'AddController@addYoutubeFile');
Route::get('/viewChest/{id}/add/soundcloud', 'AddController@addSoundcloudFile');
Route::get('/viewChest/{id}/add/slideshareSearch', 'AddController@searchSlideshare');
Route::get('/viewChest/{id}/add/slideshare', 'AddController@addSlideshareFile');
Route::get('/viewChest/{id}/add/soundcloud', 'AddController@addSoundcloudFile');
Route::get('/search/processSlideshare/{id}', 'AddController@processSlideshare');


Route::get('/viewFile/{id}', 'FileController@view');
Route::get('/delete/file/{id}', 'FileController@delete');
Route::get('/download/file/{id}', 'FileController@download');


Route::post('/upload/{id}', 'UploadController@uploadLocalFile');
Route::post('/upload/youtube/{id}', 'UploadController@uploadYoutubeFile');
Route::post('/upload/facebook/{id}', 'UploadController@uploadFacebookFile');
Route::post('/upload/slideshare/{id}', 'UploadController@uploadSlideshareFile');
Route::post('/upload/soundcloud/{id}', 'UploadController@uploadSoundcloudFile');




