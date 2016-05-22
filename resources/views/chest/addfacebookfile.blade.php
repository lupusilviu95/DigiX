@extends('layouts.add')


@section('content')


<div class="container">
	<div class="row">
		<div class="col-md-5 col-md-offset-3">
			<form action="/upload/{{$id}}" method="POST" enctype="multipart/form-data">
      					<input type="hidden" name="chestid" value="{{$id}}">
      					<div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">

               				 <!-- Wrapper for slides -->
                			<div id="carouselWrapper" class="carousel-inner" role="listbox">

		                	</div>

                			<!-- Left and right controls -->
                			<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    			<span class="sr-only">Previous</span>
                			</a>
                			<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    			<span class="sr-only">Next</span>
                			</a>
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

  			  			<button type="submit" class="btn btn-default" disabled>Submit</button>

            		</form>
           </div>
        </div>
     </div>

@endsection