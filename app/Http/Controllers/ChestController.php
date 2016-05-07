<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\File;
use App\DatabaseInteraction;
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

        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
       
        if($owner==1)
        {
	            
	    	$_SESSION['currChest'] = $id;
            $files=$db->getFilesForChest($id);
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
        
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();

        $cap=$request->capacitate;
        $desc=$request->description;
        $nm=$request->chestName;

        $id_cufar=$db->newChest($user,$cap,$cap,$desc,$nm);
       
        $filepath='userdata/users/user'.$user.'/chest'.$id_cufar;
        Storage::disk('local')->makeDirectory($filepath);

        return redirect('/dashboard');

    }

    public function addFile($id) {



        session_start();
        session_destroy();


        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            

            return view('chest.addFile',compact('id'));

        }
        else  return redirect('/dashboard');
        
       
    }

     public function delete($id) {
        
      $user=Auth::user()->id;
      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $db->deleteChest($id);
      $filepath='userdata/users/user'.$user.'/chest'.$id;
      Storage::disk('local')->deleteDirectory($filepath);
  
     return redirect()->back();
    }
    public function search($id) {


        $_SESSION['currChest'] = $id;
        $search_term=$_GET['srch-term'];
        if(strpos($search_term,';')){
            $tokens=explode(";",$search_term);
            $tags=explode(",",$tokens[0]);
            $in_list="('";

            foreach ($tags as $tag) {
                $in_list=$in_list.$tag."','";
            }
            $in_list=rtrim($in_list,"'");
            $in_list=rtrim($in_list,",");
            $in_list=$in_list.")";
            $rudenie_in="('".$tokens[1]."')";
            $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
            $db->connect();
            $files=$db->searchFilesByTagsAndRelative($id,$in_list,$rudenie_in);
            return view('chest.search',compact('files'));
            
        }
        else  {
            $tags=explode(",",$search_term);
            $in_list="('";

            foreach ($tags as $tag) {
                $in_list=$in_list.$tag."','";
            }
            $in_list=rtrim($in_list,"'");
            $in_list=rtrim($in_list,",");
            $in_list=$in_list.")";
            $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
            $db->connect();
            $files=$db->searchFilesByTags($id,$in_list);
            return view('chest.search',compact('files'));
          
            
        }
        
    }
}
