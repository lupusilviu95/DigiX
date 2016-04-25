<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\File;
use Storage;

class ChestController extends Controller

{	 
	 private $starting_path;
	 public function __construct()
    {
        $this->middleware('auth');
        $starting_path='userdata';
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
	    	$_SESSION['currChest'] = $id;
            return view('chest.index',compact('files'));

	    }
    	else  return redirect('/dashboard');
    	
    }

    public function newChest ( ) {

    	return view('chest.newChest');
    	
    }

    public function create (Request $request) {
    	


    	$this->validate($request,[
    		'description'=>'required', 
    		'chestName'=>'required'
    		]);
    	$user=Auth::user()->id;
        

        $conn = oci_connect('student', 'STUDENT', 'localhost/XE');
        if (!$conn) 
        {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        } 
        $id_cufar=null;
    	$maxid=oci_parse($conn, 'select max(chest_id) from chests');
      
        oci_execute($maxid);
        oci_fetch($maxid);
        $id_cufar=oci_result($maxid,'MAX(CHEST_ID)');
        $id_cufar++;
        $insert_stmt='insert into chests values(:idcufar,:iduser,:capacity,:freeslots,:descr,:nume)';
        $handle=oci_parse($conn, $insert_stmt);
        oci_bind_by_name($handle,":iduser",$user);
        $cap=$request->capacitate;
        oci_bind_by_name($handle,":capacity",$cap);
        oci_bind_by_name($handle,":freeslots",$cap);
        $desc=$request->description;
        oci_bind_by_name($handle,":descr",$desc);
        $nm=$request->chestName;
        oci_bind_by_name($handle,":nume",$nm);
        oci_bind_by_name($handle,":idcufar",$id_cufar);
        oci_execute($handle);
        oci_commit($conn);
        $filepath='userdata/users/user'.$user.'/chest'.$id_cufar;
        Storage::disk('local')->makeDirectory($filepath);

        return redirect('/dashboard');

    }

    public function addFile($id) {
        return "Add file to chest with id=".$id;
    }
}
