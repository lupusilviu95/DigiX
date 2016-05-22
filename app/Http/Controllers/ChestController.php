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
        $_SESSION['chest']=$id;


        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            
            return \Redirect::to('/viewChest/'.$id.'/add/local');    
            return view('chest.addlocalfile',compact('id'));

        }
        else  return redirect('/dashboard');
        
       
    }
    public function addLocalFile($id) {

       session_start();
        $_SESSION['chest']=$id;


        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            

            return view('chest.addlocalfile',compact('id'));

        }
        else  return redirect('/dashboard');
        
       
    }
    public function addFacebookFile($id) {

        
session_start();
        $_SESSION['chest']=$id;

        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            

            return view('chest.addfacebookfile',compact('id'));

        }
        else  return redirect('/dashboard');
        
       
    }
    public function addSoundcloudFile($id) {

       session_start();
        $_SESSION['chest']=$id;

        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            

            return view('chest.addsoundcloudfile',compact('id'));

        }
        else  return redirect('/dashboard');
        
       
    }
    public function addSlideshareFile($id) {

        session_start();
        $_SESSION['chest']=$id;


        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            

            return view('chest.addslidesharefile',compact('id'));

        }
        else  return redirect('/dashboard');
        
       
    }
    public function addYoutubeFile($id) {
        session_start();
        $_SESSION['chest']=$id;
        

        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            

            if (\Session::has('token'))  
            {
                $youtube = \App::make('youtube');
          

                $channelsResponse = $youtube->channels->listChannels('contentDetails', array('mine' => 'true',));
         
                $videos=null;
                foreach ($channelsResponse['items'] as $channel) {
                  // Extract the unique playlist ID that identifies the list of videos
                  // uploaded to the channel, and then call the playlistItems.list method
                  // to retrieve that list.
                  $uploadsListId = $channel['contentDetails']['relatedPlaylists']['uploads'];

                  $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
                    'playlistId' => $uploadsListId,
                    'maxResults' => 50
                  ));

                 
                  foreach ($playlistItemsResponse['items'] as $playlistItem) {
                  
                    $videos[]=$playlistItem;
                  }
                }
         
      
      
  
          }
            else return Redirect::to("/");

            return view('chest.addyoutubefile',compact('videos','id'));

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

    public function edit ($id) {

        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            
            $cufar=$db->getChestData($id);
            return view('chest.edit',compact('cufar'));

        }
        else  return redirect('/dashboard');

        
        
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'description'=>'required', 
            'chestName'=>'required'
            ]);

        $desc=$request->description;
        $name=$request->chestName;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $db->updateChest($id,$name,$desc);
        return redirect('/dashboard');

    }
}
