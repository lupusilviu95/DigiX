<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Chest;
use App\DatabaseInteraction;
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user=Auth::user()->id;
       

        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $cufere=$db->getChestsForUser($user);
        return view('dashboard',compact('cufere'));
    }
}