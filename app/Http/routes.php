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

Route::get('/loginY/{id}', ['uses' => 'GoogleLoginController@index', 'as' => 'login']);
Route::get('/loginCallback', ['uses' => 'GoogleLoginController@store', 'as' => 'loginCallback']);
Route::get('/videos','YouTubeApiController@videos');


Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/search','DashboardController@search');

Route::get('/viewChest/{id}','ChestController@index');
Route::get('/viewChest/{id}/add','ChestController@addFile');
Route::get('/viewChest/{id}/add/local','ChestController@addLocalFile');
Route::get('/viewChest/{id}/add/facebook','ChestController@addFacebookFile');
Route::get('/viewChest/{id}/add/youtube','ChestController@addYoutubeFile');
Route::get('/viewChest/{id}/add/soundcloud','ChestController@addSoundcloudFile');
Route::get('/viewChest/{id}/add/slideshare','ChestController@addSlideshareFile');
Route::get('/newChest','ChestController@newChest');
Route::get('/delete/chest/{id}','ChestController@delete');
Route::get('/edit/chest/{id}','ChestController@edit');
Route::patch('/update/chest/{id}','ChestController@update');
Route::post('/create','ChestController@create');

Route::get('/viewFile/{id}','FileController@view');
Route::get('/delete/file/{id}','FileController@delete');
Route::get('/download/file/{id}','FileController@download');
Route::post('/upload/{id}','FileController@upload');
Route::post('/upload/youtube/{id}','FileController@uploadYoutube');
Route::post('/upload/facebook/{id}','FileController@uploadFaceBook');

Route::get('/viewChest/{id}/search','ChestController@search');

Route::get('/tweetFile/{id}', function($id)
{  
	$user=Auth::user()->id;
    $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
    $db->connect();
    $owner=$db->verifyFileOwnership($user,$id);
    if($owner==1){
    	  $root='C:\wamp\www\DigiX\storage\app\\';
          $cale=$db->getFilePath($id);
		  $uploaded_media = Twitter::uploadMedia(['media' =>           File::get($root.$cale)]);
		  Twitter::postTweet(['status' => 'I am using #DigiX', 'media_ids' =>   $uploaded_media->media_id_string]);
		  return redirect('/twitter/logout');
		}
	else return redirect('/dashboard');
});



Route::get('/tweet/{id}', ['as' => 'twitter.login', function($id){
    // your SIGN IN WITH TWITTER  button should point to this route
    $sign_in_twitter = true;
    $force_login = false;

    // Make sure we make this request w/o tokens, overwrite the default values in case of login.
    Twitter::reconfig(['token' => '', 'secret' => '']);
    $token = Twitter::getRequestToken(route('twitter.callback'));

    if (isset($token['oauth_token_secret']))
    {
        $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

        Session::put('oauth_state', 'start');
        Session::put('oauth_request_token', $token['oauth_token']);
        Session::put('oauth_request_token_secret', $token['oauth_token_secret']);
        Session::put('fileid',$id);

        return Redirect::to($url);
    }

    return Redirect::route('twitter.error');
}]);

Route::get('/twitter/callback', ['as' => 'twitter.callback', function() {
    // You should set this route on your Twitter Application settings as the callback
    // https://apps.twitter.com/app/YOUR-APP-ID/settings
    if (Session::has('oauth_request_token'))
    {
        $request_token = [
            'token'  => Session::get('oauth_request_token'),
            'secret' => Session::get('oauth_request_token_secret'),
        ];

        Twitter::reconfig($request_token);

        $oauth_verifier = false;

        if (Input::has('oauth_verifier'))
        {
            $oauth_verifier = Input::get('oauth_verifier');
        }

        // getAccessToken() will reset the token for you
        $token = Twitter::getAccessToken($oauth_verifier);

        if (!isset($token['oauth_token_secret']))
        {
            return Redirect::route('twitter.login')->with('flash_error', 'We could not log you in on Twitter.');
        }

        $credentials = Twitter::getCredentials();

        if (is_object($credentials) && !isset($credentials->error))
        {
            // $credentials contains the Twitter user object with all the info about the user.
            // Add here your own user logic, store profiles, create new users on your tables...you name it!
            // Typically you'll want to store at least, user id, name and access tokens
            // if you want to be able to call the API on behalf of your users.

            // This is also the moment to log in your users if you're using Laravel's Auth class
            // Auth::login($user) should do the trick.

            Session::put('access_token', $token);
            $id=Session::get('fileid');
            return Redirect::to("/tweetFile/".$id);
        }

        return Redirect::route('twitter.error')->with('flash_error', 'Crab! Something went wrong while signing you up!');
    }
}]);

Route::get('/twitter/error', ['as' => 'twitter.error', function(){
    // Something went wrong, add your own error handling here
}]);

Route::get('/twitter/logout', ['as' => 'twitter.logout', function(){
    Session::forget('access_token');
    return Redirect::to('/dashboard')->with('flash_notice', 'Succesfully posted tweet!');
}]);

Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');
