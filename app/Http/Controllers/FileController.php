<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\DatabaseInteraction;

class FileController extends Controller
{

   protected $request;

   public function __construct(\Illuminate\Http\Request $request)
   {
       $this->request = $request;
   }
   public function delete($id) {
   		
      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $db->deleteFile($id);
  
   		return redirect()->back();
   	}
   	 public function upload(Request $request) {
   	 	$this->validate($request,[ 
   	 		'file' =>'required',
    		'tags'=>array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

    		]);
   	 	$user=Auth::user()->id;
   	 	$id=$request->chestid;
   	 	$filepath='userdata/users/user'.$user.'/chest'.$id;
   	 	$filename='test.pdf';
	    $this->request->file('file')->move($filepath,$filename);
	        //return redirect('/dashboard');
    	
   	 	
   	 	//return redirect()->back();
   	 }

}
