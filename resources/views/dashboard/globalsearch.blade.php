@extends('layouts.search')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
            	 @if($files!=null)
                <div class="panel-heading">Search results:</div>
            	 <table class="table table-bordered" id="myTable">
                     <tbody>
            		 <tr>
   						<th>File name</th>
   						<th>Type</th>
              <th>Location</th>
 					 </tr>
                    @foreach($files as $file)
                    <tr class="clickable-row" id="{{$file->fileid}}" onclick="pop(this)">
                    	<td><a href="/viewFile/{{$file->fileid}}">{{$file->name}}</a></td>
                    	<td>{{$file->type}}</td>
                      <td><a href="/viewChest/{{$file->chestid}}">View in chest</a></td>
                    </tr>    
                    @endforeach
                  </tbody>
                  </table>
                @else 
                    <div class="panel-heading">The search returned 0 files</div>
                @endif

            </div>
        </div>
    </div>
</div>

	
@endsection