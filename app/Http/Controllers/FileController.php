<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class FileController extends Controller
{
   public function delete($id) {
   		$conn = oci_connect('student', 'STUDENT', 'localhost/XE');
        if (!$conn) 
        {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }       

        $stid = oci_parse($conn, 'delete from files where file_id=:idf');
        oci_bind_by_name($stid, ':idf', $id);
        oci_execute($stid);
        
   		return redirect()->back();
   	}
}
