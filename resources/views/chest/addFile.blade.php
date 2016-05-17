@extends('layouts.app')
  <link rel="stylesheet"  href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}


@section('facebook')

<script>
  	window.fbAsyncInit = function() {
    	FB.init({
     	appId      : '1076282755772512',
     	xfbml      : true,
      	version    : 'v2.6'
    	});
  	};

  	(function(d, s, id){
    	var js, fjs = d.getElementsByTagName(s)[0];
     	if (d.getElementById(id)) {return;}
     	js = d.createElement(s); js.id = id;
     	js.src = "//connect.facebook.net/en_US/sdk.js";
     	fjs.parentNode.insertBefore(js, fjs);
   	}(document, 'script', 'facebook-jssdk'));

   	function authenticate(){
   		FB.getLoginStatus(function (response) {
     		if (response.status === 'connected') {
        		getPhotos();
     		} else {
        		console.log("not connected");
        		FB.login(function(response) {
				    if (response.authResponse) {
					    location.reload();
				    } else {
				        console.log('User cancelled login or did not fully authorize.');
				    }
				},{scope: 'user_photos'});
     		}
  		});
   	};

  	function getPhotos(){

  		FB.api('/me?fields=id,name,photos', function(response){
  			var idData
			if(response){
				for (var i = 0; i < response.photos.data.length; i++) {
						if(i==0){
							createActiveDiv(i);
						}
						else{
							createDiv(i);
						}
						idData=response.photos.data[i];
						putImg(idData.id, i);
				}
			}
			else
				console.log("No Photos");

			/*var respData=$.getJSON(response.photos.paging.next, function(){});
			var j=0;
			var object;
			console.log(respData);
			/*object=respData.responseJSON;
			console.log(object.data);*/
			/*for(var j=0; j< respData.responseJSON.data.length;j++){
				console.log("enter for");
				console.log(respData.responseJSON.data[j].id);
			}
			while(respData.next){
				j=j+1;
				console.log(j);
				respData=$.getJSON(respData.paging.next, function(){});
				console.log("done");
			}*/
		});

		
 	};

 	function createActiveDiv(divId){
 		var div = document.createElement("div");
 		div.setAttribute('id', divId);
 		div.setAttribute('class', 'item active');
 		document.getElementById("carouselWrapper").appendChild(div);
 	}

 	function createDiv(divId){
 		var div = document.createElement("div");
 		div.setAttribute('id', divId);
 		div.setAttribute('class', 'item');
 		document.getElementById("carouselWrapper").appendChild(div);
 	};

 	function putImg(id, divId){
 		FB.api('/' + id + '?fields=images', function(response){
			var img = document.createElement("img");
			img.src=response.images[1].source;
			img.width=173;
			img.height=130;
			img.alt=response.link;
			document.getElementById(divId).appendChild(img);
		});

 	}
</script>

@endsection


@section('content')

<script src="/js/carousel.js"></script>
 <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
 <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

<div class="container">
	<div class="row">
		<div class="col-md-5 col-md-offset-3">
		<ul class="nav nav-pills">
            	<li class="active"><a data-toggle="pill" href="#local">Local File</a></li>
    			<li><a data-toggle="pill" href="#facebook" onclick="authenticate()">Facebook</a></li>
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