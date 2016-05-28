<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\DatabaseInteraction;
use Storage;
use File;
use Response;

class FileController extends Controller
{

    private $root = "";
    protected $request;

    protected $clientID = '1e6c18cd3bd30bc2add78468fd6fe4c3', $clientSecret = '12bec80747a6a4503d092f3a7087032a', $callbackUri = 'http://localhost:8000/SCcallback';
    protected $useridURL = 'https://api.soundcloud.com/me.json?oauth_token=';


    public function __construct(\Illuminate\Http\Request $request)
    {

        $this->request = $request;
        $this->root = public_path() . "\\";
        $this->middleware('auth');


    }

    public function delete($id)
    {

        if (!Auth::check())
            return redirect('/');
        else {

            $user = Auth::user()->id;
            $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
            $db->connect();
            $owner = $db->verifyFileOwnership($user, $id);
            if ($owner == 1) {

                $origin = $db->isLocal($id);
                if ($origin == 1) {
                    $cale = $db->getFilePath($id);
                    File::delete($cale);
                    $db->deleteFile($id);

                } else {
                    $db->deleteFile($id);
                }


                return redirect()->back();
            } else {
                return redirect('/dashboard');
            }
        }

    }

    public function download($id)
    {


        if (!Auth::check())
            return redirect('/');
        else {

            $user = Auth::user()->id;

            $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
            $db->connect();
            $owner = $db->verifyFileOwnership($user, $id);
            if ($owner == 1) {
                $origin = $db->isLocal($id);
                if ($origin == 1) {
                    $cale = $db->getFilePath($id);
                    return response()->download($this->root . $cale);

                } else {
                    return redirect()->back()->with("flash_info", "We're sorry,but this is not available for this type of resource");
                }

            } else {
                return redirect('/dashboard');
            }
        }

    }

    public function view($id)
    {

        if (!Auth::check())
            return redirect('/');
        else {
            $user = Auth::user()->id;
            $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
            $db->connect();
            $owner = $db->verifyFileOwnership($user, $id);
            if ($owner == 1) {
                $origin = $db->isLocal($id);
                if ($origin == 1) {
                    $cale = $db->getFilePath($id);
                    return response()->file($this->root . $cale);

                } else {
                    return redirect()->back()->with("flash_info", "We're sorry,but this is not available for this type of resource");
                }


            } else {
                return redirect('/dashboard');
            }
        }

    }


}
