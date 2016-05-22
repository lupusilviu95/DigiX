<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Redirect;

class YouTubeApiController extends Controller
{
   public function videos()
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
}
}
