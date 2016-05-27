<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\File;
use App\DatabaseInteraction;
use Storage;
use Session;
use DOMDocument;
use App\Slideshow;
use App\SoundcloudFile;     
use Njasm\Soundcloud\SoundcloudFacade;

class ChestController extends Controller

{	 

    protected $clientID='1e6c18cd3bd30bc2add78468fd6fe4c3',$clientSecret='12bec80747a6a4503d092f3a7087032a',$callbackUri='http://localhost:8000/SCcallback';
    protected $useridURL='https://api.soundcloud.com/me.json?oauth_token=';
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
  
    public function SearchSlideShare($id) {

       session_start();
        $_SESSION['chest']=$id;

        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {
            

            return view('chest.searchUserForm',compact('id'));

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
     public function addSoundcloudFile($id){

        session_start();
        $_SESSION['chest']=$id;
        

        $user=Auth::user()->id;
        $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $owner=$db->verifyOwnership($user,$id);
        
        if($owner==1)
        {

            $code=\Session::get('code');
            $facade = new SoundcloudFacade($this->clientID, $this->clientSecret, $this->callbackUri);
            $response=$facade->codeForToken($code);
            $token=Session::get('sc_token');
            $content = file_get_contents($this->useridURL.$token);
            $json = json_decode($content, true);
            $uid=$json["id"];
            $tracksURL="https://api.soundcloud.com/tracks?user_id="
            .$uid.
            "&limit=100&format=json&oauth_token="
            .$token;
            $tracks=file_get_contents($tracksURL);
            $json_tracks=json_decode($tracks,true);
            $size=sizeof($json_tracks);
            $songs=null;
            for($i=0;$i<$size;$i++){
                $song=new SoundcloudFile();
                $song->title=$json_tracks[$i]['title'];
                $song->embedurl=$json_tracks[$i]['uri'];
                $song->url=$json_tracks[$i]['permalink_url'];
                $songs[]=$song;
            }
            
            return view('chest.addsoundcloudfile',compact('songs','id'));
        }
        else return Redirect::to('/');

         
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

    public function processSlideshare(Request $request, $id) {
        
        session_start();
        $_SESSION['chest']=$id;
        

        $this->validate($request,[
            'username'=>'required'
            ]);

        $name=Auth::user()->id;
        $user=$request->username;

        $url="https://www.slideshare.net/api/2/get_slideshows_by_user";


        $api_key="Zg6wXN6k";
        $secret_key = "extxY8qg";
        $time = time();
        $hash = hash('sha1',$secret_key.$time);
        $limit=10;


        $req=$url ."?api_key=".$api_key
            ."&ts=".$time
            ."&hash=".$hash
            ."&limit=".$limit
            ."&username_for=".$user;

          
       
        $dom=new DOMDocument();
        $dom->load($req);

        
        $messageNodes = $dom->getElementsByTagName('Message'); 
        if($messageNodes->length != 0)
        {
           
           $flash_error=Session::get('flash_error');
           return redirect()->back()->withInput()->with('flash_error', 'User Not Found');
        }

        
        else 

        {
            $slideshows = $dom->getElementsByTagName('Slideshow');
            $slides=null;
            foreach ($slideshows as $slideshow) {
                $urls=$slideshow->getElementsByTagName('URL');
                $titles=$slideshow->getElementsByTagName('Title');
                $embeds=$slideshow->getElementsByTagName('SlideshowEmbedUrl');
                $slide=new Slideshow();
                $slide->title=$titles->item(0)->nodeValue;
                $slide->url=$urls->item(0)->nodeValue;
                $slide->embedlink=$embeds->item(0)->nodeValue;
                $slides[]=$slide;
            }
           
            return view('chest.addslidesharefile',compact('slides','id'));
            //var_dump($slides);
        
        }

    }



}
