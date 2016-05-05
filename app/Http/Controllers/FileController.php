<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DatabaseInteraction;

class FileController extends Controller
{
   public function delete($id) {
   		
      $db=new DatabaseInteraction('student', 'STUDENT', 'localhost/XE');
      $db->connect();
      $db->deleteFile($id);
  
   		return redirect()->back();
   	}
}
