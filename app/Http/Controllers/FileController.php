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
   private  $root='C:\wamp\www\DigiX\storage\app\\';
   protected $request;

   public function __construct(\Illuminate\Http\Request $request)
   {  

       $this->request = $request;
      
   }
   public function delete($id) {
   		
      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $cale=$db->getFilePath($id);
      Storage::delete($cale);
      $db->deleteFile($id);
      
   		return redirect()->back();
   	}
    public function download ($id) {

      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $cale=$db->getFilePath($id);
      return response()->download($this->root.$cale);
      
      
    }
    public function view ($id) {

      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $cale=$db->getFilePath($id);
      return response()->file($this->root.$cale);
      
      
    }

   	 public function upload(Request $request) {
   	 	$this->validate($request,[ 
   	 		'file' =>'required',
    		'tags'=>array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

    		]);
   	 	$user=Auth::user()->id;
   	 	$id=$request->chestid;
   	
      $file=$this->request->file('file');

      $name = $file->getClientOriginalName();
      $filepath='userdata/users/user'.$user.'/chest'.$id.'/'.$name;

      $extension = $file->getClientOriginalExtension();

      Storage::disk('local')->put($filepath, File::get($file));

      $tags=$request->tags;
      $separated_tags=explode(",",$tags);

      $rudenie=$request->rudenie;

      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $fileid=$db->addFile($id,$name,$extension,$filepath);

      foreach ($separated_tags as $tag) {
        $db->addTagToFile($fileid,$tag);
       }
       if(strcmp($rudenie,"-none-")){
        $db->addRelativeToFile($fileid,$rudenie);
       }

      return redirect('/viewChest/'.$id);

   	 }

}
