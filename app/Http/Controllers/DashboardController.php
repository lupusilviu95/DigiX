<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Chest;
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
       

        $conn = oci_connect('student', 'STUDENT', 'localhost/XE');
        if (!$conn) 
        {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }       

        $stid = oci_parse($conn, 'SELECT  chests.name,chest_id,user_id,capacity,freeslots,description from users join chests on user_id=id and user_id=:idu');
        oci_bind_by_name($stid, ':idu', $user);
        oci_execute($stid);


        $cufere=null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
        {
               $cufar=new Chest();
               $cufar->id_cufar=$row['CHEST_ID'];
               $cufar->id_user=$row['USER_ID'];
               $cufar->capacity=$row['CAPACITY'];
               $cufar->freeSlots=$row['FREESLOTS'];
               $cufar->description=$row['DESCRIPTION'];
               $cufar->name=$row['NAME'];

               $cufere[]=$cufar;

        
        }
     
        return view('dashboard',compact('cufere'));
    }
}