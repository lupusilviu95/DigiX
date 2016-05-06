@extends('layouts.app')


@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-5 col-md-offset-3">
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
	</div>
</div>
@endsection