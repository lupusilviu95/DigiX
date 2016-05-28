<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SoundcloudFile;
use App\Http\Requests;
use Redirect;
use Njasm\Soundcloud\SoundcloudFacade;


class SoundcloudController extends Controller
{
    protected $clientID = '1e6c18cd3bd30bc2add78468fd6fe4c3', $clientSecret = '12bec80747a6a4503d092f3a7087032a', $callbackUri = 'http://localhost:8000/SCcallback';
    protected $useridURL = 'https://api.soundcloud.com/me.json?oauth_token=';


    public function register($id)
    {
        $facade = new SoundcloudFacade($this->clientID, $this->clientSecret, $this->callbackUri);
        $url = $facade->getAuthUrl();
        \Session::put('cufar', $id);
        return Redirect::to($url);
    }

    public function callback()
    {
        $id = \Session::get('cufar');
        $code = $_GET['code'];
        \Session::put('code', $code);
        $facade = new SoundcloudFacade($this->clientID, $this->clientSecret, $this->callbackUri);
        $response = $facade->codeForToken($code);
        $token = $response->bodyArray()['access_token'];
        \Session::put('sc_token', $token);
        return Redirect::to('/viewChest/' . $id . '/add');

    }
}
