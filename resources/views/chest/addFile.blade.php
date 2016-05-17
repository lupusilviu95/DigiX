@extends('layouts.app')
  <link rel="stylesheet"  href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-5 col-md-offset-3">
		<ul class="nav nav-pills">
            	<li class="active"><a data-toggle="pill" href="#local">Local File</a></li>
    			<li><a data-toggle="pill" href="#facebook">Facebook</a></li>
   				<li><a data-toggle="pill" href="#youtube">Youtube</a></li>
    			<li><a data-toggle="pill" href="#soundcloud">SoundCloud</a></li>
  			</ul>

			<div class="tab-content">
				<div id="local" class="tab-pane fade in active">
					<h3>Local File</h3>
					<form action="/upload/{{$id}}" method="POST" enctype="multipart/form-data">
			 		{!! csrf_field() !!}
			 		<input type="hidden" name="chestid" value="{{$id}}">
			  		<div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
			    		<label for="file">File input</label>
			    		<input type="file" id="file" name="file">
			    
			   			 @if ($errors->has('file'))

						<span class="help-block">
						<strong>{{ $errors->first('file') }}</strong>
						</span>
						@endif
			  		</div>
			  		<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
    					<label for="tags">Tags</label>
    					<input type="text" class="form-control" id="tags" name="tags" placeholder="Enter tags separated by comma" value="{{ old('tags') }}">
    					@if ($errors->has('tags'))
							<span class="help-block">
								<strong>{{ $errors->first('tags') }}</strong>
							</span>
						@endif
  			  		</div>
			  		<div class="form-group">
		  	 			<label for="rudenie">Relatives</label>
					 	<select class="form-control" id="rudenie" name="rudenie">
					 		<option>-none-</option>
		  					<option>mama</option>
		 					<option>tata</option>
		  					<option>frate</option>
		  					<option>sora</option>
		  					<option>bunic</option>
		  					<option>bunica</option>
		  					<option>var</option>
		  					<option>verisoara</option>
		  					<option>unchi</option>
		  					<option>matusa</option>
		 				</select>
  			  		</div>
			  			<button type="submit" class="btn btn-default">Submit</button>
					</form>
				</div>

				<div id="facebook" class="tab-pane fade">
      			<h3>Facebook photo</h3>
      			<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    			</div>

    			<div id="youtube" class="tab-pane fade">
      			<h3>Youtube video</h3>
      			<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    			</div>

    			<div id="soundcloud" class="tab-pane fade">
      			<h3>Soundcloud audio</h3>
      			<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    			</div>

    		</div>
	</div>
</div>
@endsection