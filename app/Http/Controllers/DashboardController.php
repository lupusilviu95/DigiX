<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Chest;
use App\DatabaseInteraction;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->id;


        $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
        $db->connect();
        $cufere = $db->getChestsForUser($user);
        return view('dashboard', compact('cufere'));
    }

    public function search()
    {
        $search_term = $_GET['srch-term'];

        $user = Auth::user()->id;
        if (strpos($search_term, ';')) {
            $tokens = explode(";", $search_term);
            $tags = explode(",", $tokens[0]);
            $in_list = "('";

            foreach ($tags as $tag) {
                $in_list = $in_list . $tag . "','";
            }
            $in_list = rtrim($in_list, "'");
            $in_list = rtrim($in_list, ",");
            $in_list = $in_list . ")";
            $rudenie_in = "('" . $tokens[1] . "')";
            $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
            $db->connect();
            $files = $db->GlobalSearchFilesByTagsAndRelative($user, $in_list, $rudenie_in);
            return view('dashboard.globalsearch', compact('files'));

        } else {
            $tags = explode(",", $search_term);
            $in_list = "('";

            foreach ($tags as $tag) {
                $in_list = $in_list . $tag . "','";
            }
            $in_list = rtrim($in_list, "'");
            $in_list = rtrim($in_list, ",");
            $in_list = $in_list . ")";
            $db = new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
            $db->connect();
            $files = $db->GlobalSearchFilesByTags($user, $in_list);
            return view('dashboard.globalsearch', compact('files'));


        }
    }
}