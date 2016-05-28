<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Redirect;

class GoogleLoginController extends Controller
{
    public function index(\App\GoogleLogin $ga, $id)
    {
        if ($ga->isLoggedIn()) {
            return Redirect::to('/');
        }

        $loginUrl = $ga->getLoginUrl();
        \Session::put('cufar', $id);
        return Redirect::to($loginUrl);
    }

    public function store(\App\GoogleLogin $ga)
    {
        // User rejected the request
        if (Input::has('error')) {
            dd(\Input::get('error'));
        }

        if (Input::has('code')) {
            $code = Input::get('code');
            $ga->login($code);
            $id = \Session::get('cufar');
            return Redirect::to('/viewChest/' . $id . '/add');
        } else {
            throw new \InvalidArgumentException("Code attribute is missing.");
        }//else
    }
}
