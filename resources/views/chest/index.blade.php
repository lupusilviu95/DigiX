@extends('layouts.chest')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
            	 @if($files!=null)
            	 <table class="table table-hover table-bordered">
            		 <tr>
   						<th>File name</th>
   						<th>Type</th>
 					 </tr>
                    @foreach($files as $file)
                    <tr>
                    	<td>{{$file->name}}</td>
                    	<td>{{$file->type}}</td>
                    </tr>    
                    @endforeach
                  </table>
                @else 
                    <div class="panel-heading">Cufar gol</div>
                @endif

            </div>
        </div>
    </div>
</div>
	
@endsection