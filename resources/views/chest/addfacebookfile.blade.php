@extends('layouts.add')

@section('facebook')

<script>
    window.fbAsyncInit = function() {
        FB.init({
        appId      : '1076282755772512',
        xfbml      : true,
        version    : 'v2.6'
        });
        document.getElementById('button').disabled = true;
        authenticate();
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
                document.getElementById('button').disabled = false;
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

    function formSubmit(){
        var actDiv=document.getElementsByClassName("item active")[0];
        var image = actDiv.getElementsByTagName("img");
        var source = image[0].getAttribute("src");
        console.log(source);
        var form = document.forms['FBform'];
        form.elements["source"].value=source;
        console.log(form);
        form.submit();
        
    }
</script>

@endsection

@section('content')


<div class="container">
	<div class="row">
		<div class="col-md-5 col-md-offset-3">

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

			<form action="/upload/facebook/{{$id}}" method="POST" id="FBform">
                {!! csrf_field() !!}
      			<input type="hidden" name="chestid" value="{{$id}}">
                <input type="hidden" name="source" id="source" value="">
      			

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

  			  	<button type="button" class="btn btn-default" onclick="formSubmit()" id="button">Submit</button>

            </form>
        </div>
    </div>
</div>

@endsection