<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\File;

class ChestController extends Controller
{
	 public function __construct()
    {
        $this->middleware('auth');
    }


    public function index($id) {

    	$user=Auth::user()->id;
       

        $conn = oci_connect('student', 'STUDENT', 'localhost/XE');
        if (!$conn) 
        {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        } 


        $verify=oci_parse($conn, 'select 1 from chests join users on id=user_id where users.id=:idu and chest_id=:cufarid');
        oci_bind_by_name($verify,':idu', $user);
        oci_bind_by_name($verify,':cufarid' ,$id);
        oci_execute($verify);


        $owner=0;
        while ($row = oci_fetch_array($verify, OCI_ASSOC+OCI_RETURN_NULLS)) 
        {
        	$owner=$row['1'];
        }

        if($owner==1)
        {
	        $stid = oci_parse($conn, 'select name,type,file_id,chest_id,path from files where chest_id=:idc' );
	        oci_bind_by_name($stid, ':idc', $id);
	        oci_execute($stid);
	        $files=null;
	        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
	        {
	        	$file=new File();
	        	$file->fileid=$row['FILE_ID'];
	        	$file->chestid=$row['CHEST_ID'];
	        	$file->type=$row['TYPE'];
	        	$file->name=$row['NAME'];
	        	$file->path=$row['PATH'];

	        	$files[]=$file;

	        }
	    	return view('chest.index',compact('files'));
	    }
    	else  return redirect('/dashboard');
    	
    }
}
