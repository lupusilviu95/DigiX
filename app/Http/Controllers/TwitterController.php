<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Twitter;
use Redirect;
use Illuminate\Support\Facades\Input;
use Auth;
use App\DatabaseInteraction;
use File;


class TwitterController extends Controller
{
    public function tweetFile($id)
    {
        $user = Auth::user()->id;
        $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner = $db->verifyFileOwnership($user, $id);
        if ($owner == 1) {
            $root = public_path() . "\\";
            $cale = $db->getFilePath($id);
            $uploaded_media = Twitter::uploadMedia(['media' => File::get($root . $cale)]);
            Twitter::postTweet(['status' => 'I am using #DigiX', 'media_ids' => $uploaded_media->media_id_string]);
            return redirect('/twitter/logout');
        } else return redirect('/dashboard');

    }

    public function tweet($id)
    {
        $sign_in_twitter = true;
        $force_login = false;

        // Make sure we make this request w/o tokens, overwrite the default values in case of login.
        Twitter::reconfig(['token' => '', 'secret' => '']);
        $token = Twitter::getRequestToken(route('twitter.callback'));

        if (isset($token['oauth_token_secret'])) {
            $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

            Session::put('oauth_state', 'start');
            Session::put('oauth_request_token', $token['oauth_token']);
            Session::put('oauth_request_token_secret', $token['oauth_token_secret']);
            Session::put('fileid', $id);

            return Redirect::to($url);
        }

        return Redirect::route('twitter.error');

    }

    public function callback()
    {
        // You should set this route on your Twitter Application settings as the callback
        // https://apps.twitter.com/app/YOUR-APP-ID/settings
        if (Session::has('oauth_request_token')) {
            $request_token = [
                'token' => Session::get('oauth_request_token'),
                'secret' => Session::get('oauth_request_token_secret'),
            ];

            Twitter::reconfig($request_token);

            $oauth_verifier = false;

            if (Input::has('oauth_verifier')) {
                $oauth_verifier = Input::get('oauth_verifier');
            }

            // getAccessToken() will reset the token for you
            $token = Twitter::getAccessToken($oauth_verifier);

            if (!isset($token['oauth_token_secret'])) {
                return Redirect::route('twitter.login')->with('flash_error', 'We could not log you in on Twitter.');
            }

            $credentials = Twitter::getCredentials();

            if (is_object($credentials) && !isset($credentials->error)) {
                // $credentials contains the Twitter user object with all the info about the user.
                // Add here your own user logic, store profiles, create new users on your tables...you name it!
                // Typically you'll want to store at least, user id, name and access tokens
                // if you want to be able to call the API on behalf of your users.

                // This is also the moment to log in your users if you're using Laravel's Auth class
                // Auth::login($user) should do the trick.

                Session::put('access_token', $token);
                $id = Session::get('fileid');
                return Redirect::to("/tweetFile/" . $id);
            }

            return Redirect::route('twitter.error')->with('flash_error', 'Crab! Something went wrong while signing you up!');
        }
    }

    public function logout()
    {
        Session::forget('access_token');
        return Redirect::to('/dashboard')->with('flash_notice', 'Succesfully posted tweet!');

    }

}
