<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\DatabaseInteraction;
use Storage;
use File;
use Response;

class UploadController extends Controller
{
    protected $clientID = '1e6c18cd3bd30bc2add78468fd6fe4c3', $clientSecret = '12bec80747a6a4503d092f3a7087032a', $callbackUri = 'http://localhost:8000/SCcallback';
    protected $useridURL = 'https://api.soundcloud.com/me.json?oauth_token=';


    public function __construct(\Illuminate\Http\Request $request)
    {

        $this->request = $request;
        $this->root = public_path() . "\\";
        $this->middleware('auth');
        $starting_path = 'userdata';

    }

    public function uploadLocalFile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
            'tags' => array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);
        $user = Auth::user()->id;
        $id = $request->chestid;

        $file = $this->request->file('file');

        $name = $file->getClientOriginalName();


        $filepath = 'userdata/users/user' . $user . '/chest' . $id . '/' . $name;

        $extension = $file->getClientOriginalExtension();

        Storage::disk('uploads')->put($filepath, File::get($file));


        $tags = $request->tags;
        $separated_tags = explode(",", $tags);

        $rudenie = $request->rudenie;

        $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $origin = 'local';
        $fileid = $db->addFile($id, $name, $extension, $filepath, $origin);

        foreach ($separated_tags as $tag) {
            $db->addTagToFile($fileid, $tag);
        }
        if (strcmp($rudenie, "-none-")) {
            $db->addRelativeToFile($fileid, $rudenie);
        }

        return redirect('/viewChest/' . $id);

    }

    public function uploadYoutubeFile(Request $request)
    {
        $this->validate($request, [
            'tags' => array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);

        $user = Auth::user()->id;
        $id = $request->chestid;

        $tags = $request->tags;
        $separated_tags = explode(",", $tags);

        $name = $request->videoname;
        $filepath = $request->videoid;

        $rudenie = $request->rudenie;

        $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $origin = 'https://www.youtube.com/watch?v=' . $filepath;
        $extension = 'youtube';

        $fileid = $db->addFile($id, $name, $extension, $filepath, $origin);

        foreach ($separated_tags as $tag) {
            $db->addTagToFile($fileid, $tag);
        }
        if (strcmp($rudenie, "-none-")) {
            $db->addRelativeToFile($fileid, $rudenie);
        }

        return redirect('/viewChest/' . $id);

        return $request->all();
    }

    public function uploadFacebookFile(Request $request)
    {
        $this->validate($request, [
            'tags' => array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);

        $user = Auth::user()->id;
        $id = $request->chestid;

        $tags = $request->tags;
        $separated_tags = explode(",", $tags);

        $name = 'Facebook Photo';
        $filepath = $request->source;

        $rudenie = $request->rudenie;

        $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $origin = $request->source;
        $extension = 'facebook';

        $fileid = $db->addFile($id, $name, $extension, $filepath, $origin);

        foreach ($separated_tags as $tag) {
            $db->addTagToFile($fileid, $tag);
        }
        if (strcmp($rudenie, "-none-")) {
            $db->addRelativeToFile($fileid, $rudenie);
        }

        return redirect('/viewChest/' . $id);

        return $request->all();
    }

    public function uploadSlideshareFile(Request $request)
    {

        $this->validate($request, [
            'tags' => array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);

        $id = $request->chestid;

        $tags = $request->tags;
        $separated_tags = explode(",", $tags);

        $title = $request->slidesharename;
        $embed = $request->embedlink;

        $rudenie = $request->rudenie;

        $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $origin = $request->slideshareurl;
        $extension = 'slideshare';

        $fileid = $db->addFile($id, $title, $extension, $embed, $origin);

        foreach ($separated_tags as $tag) {
            $db->addTagToFile($fileid, $tag);
        }
        if (strcmp($rudenie, "-none-")) {
            $db->addRelativeToFile($fileid, $rudenie);
        }

        return redirect('/viewChest/' . $id);

        return $request->all();

    }

    public function uploadSoundcloudFile(Request $request)
    {

        $this->validate($request, [
            'tags' => array('required', 'regex:/^(([a-z]+))$|^(([a-z]+)[,]([a-z]+))$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+))$/i')

        ]);

        $id = $request->chestid;

        $tags = $request->tags;
        $separated_tags = explode(",", $tags);

        $title = $request->songtitle;
        $embed = $request->embedurl;

        $rudenie = $request->rudenie;

        $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $origin = $request->url;
        $extension = 'soundcloud';

        $fileid = $db->addFile($id, $title, $extension, $embed, $origin);

        foreach ($separated_tags as $tag) {
            $db->addTagToFile($fileid, $tag);
        }
        if (strcmp($rudenie, "-none-")) {
            $db->addRelativeToFile($fileid, $rudenie);
        }

        return redirect('/viewChest/' . $id);


    }

}
