@extends('layouts.chest')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
            	 @if($files!=null)
            	 <table class="table table-bordered" id="myTable">
                     <tbody>
            		 <tr>
   						<th>File name</th>
   						<th>Type</th>
              <th>Twitter</th>
 					 </tr>
                    @foreach($files as $file)
                    <tr class="clickable-row" id="{{$file->fileid}}" onclick="pop(this)">
                    	<td><a href="/viewFile/{{$file->fileid}}">{{$file->name}}</a></td>
                    	<td>{{$file->type}}</td>
                      @if($file->type=='jpg')
                      <td><a href="/tweet/{{$file->fileid}}">Tweet this</a></td>
                      @else
                      <td>N/A</td>
                      @endif
                    </tr>    
                    @endforeach
                  </tbody>
                  </table>
                @else 
                    <div class="panel-heading">Cufar gol</div>
                @endif

            </div>
        </div>
    </div>
</div>

	
@endsection