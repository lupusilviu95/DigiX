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

      if(!Auth::check())
        return redirect('/');
      else 
      {

       		$user=Auth::user()->id;
          $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
          $db->connect();
           $owner=$db->verifyFileOwnership($user,$id);
          if($owner==1){
          $cale=$db->getFilePath($id);
          Storage::delete($cale);
          $db->deleteFile($id);
          
       		return redirect()->back();
        }
        else {
            return redirect('/dashboard');
        }
      }

   	}
    public function download ($id) {

      
      if(!Auth::check())
        return redirect('/');
      else 
      {
        
          $user=Auth::user()->id;

          $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
          $db->connect();
          $owner=$db->verifyFileOwnership($user,$id);
          if($owner==1){
          $cale=$db->getFilePath($id);
          return response()->download($this->root.$cale);
          }
          else {
            return redirect('/dashboard');
          }
      }
      
    }
    public function view ($id) {

      if(!Auth::check())
        return redirect('/');
      else 
      {
          $user=Auth::user()->id;
          $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
          $db->connect();
          $owner=$db->verifyFileOwnership($user,$id);
          if($owner==1){
          $cale=$db->getFilePath($id);
          return response()->file($this->root.$cale);
          }
          else{
            return redirect('/dashboard');
          }
      }
      
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
      $origin='local';
      $fileid=$db->addFile($id,$name,$extension,$filepath,$origin);

      foreach ($separated_tags as $tag) {
        $db->addTagToFile($fileid,$tag);
       }
       if(strcmp($rudenie,"-none-")){
        $db->addRelativeToFile($fileid,$rudenie);
       }

      return redirect('/viewChest/'.$id);

   	 }

     public function uploadYoutube(Request $request){
      $this->validate($request,[ 
        'tags'=>array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);

      $user=Auth::user()->id;
      $id=$request->chestid;

      $tags=$request->tags;
      $separated_tags=explode(",",$tags);

      $name=$request->videoname;
      $filepath=$request->videoid;

      $rudenie=$request->rudenie;

      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $origin='youtube';
      $extension='youtube';

      $fileid=$db->addFile($id,$name,$extension,$filepath,$origin);

      foreach ($separated_tags as $tag) {
        $db->addTagToFile($fileid,$tag);
       }
       if(strcmp($rudenie,"-none-")){
        $db->addRelativeToFile($fileid,$rudenie);
       }

      return redirect('/viewChest/'.$id);

      return $request->all();
     }

     public function uploadFaceBook(Request $request){
      $this->validate($request,[ 
        'tags'=>array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);

      $user=Auth::user()->id;
      $id=$request->chestid;

      $tags=$request->tags;
      $separated_tags=explode(",",$tags);

      $name='FaceBook Photo';
      $filepath=$request->source;

      $rudenie=$request->rudenie;

      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $origin='facebook';
      $extension='facebook';

      $fileid=$db->addFile($id,$name,$extension,$filepath,$origin);

      foreach ($separated_tags as $tag) {
        $db->addTagToFile($fileid,$tag);
       }
       if(strcmp($rudenie,"-none-")){
        $db->addRelativeToFile($fileid,$rudenie);
       }

      return redirect('/viewChest/'.$id);

      return $request->all();
     }

     public function addslideshareFile(Request $request){

      $this->validate($request,[ 
        'tags'=>array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);

      $id=Auth::user()->id;

      $tags=$request->tags;
      $separated_tags=explode(",",$tags);

      $title=$request->slidesharename;
      $embed=$request->embedlink;

      $rudenie=$request->rudenie;

      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $origin='slideshare';
      $extension='slideshare';

      $fileid=$db->addFile($id,$title,$extension,$embed,$origin);

      foreach ($separated_tags as $tag) {
        $db->addTagToFile($fileid,$tag);
       }
       if(strcmp($rudenie,"-none-")){
        $db->addRelativeToFile($fileid,$rudenie);
       }

      return redirect('/viewChest/'.$id);

      return $request->all();

     }




}
