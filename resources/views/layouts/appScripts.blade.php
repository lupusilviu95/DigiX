<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="/css/style.css">

    <title>DigiX</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"
          integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"
            integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">


<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                DigiX
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"
        integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script src="/js/carousel.js"></script>
<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: '1076282755772512',
            xfbml: true,
            version: 'v2.6'
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function authenticate() {
        FB.getLoginStatus(function (response) {
            if (response.status === 'connected') {
                getPhotos();
            } else {
                console.log("not connected");
                FB.login(function (response) {
                    if (response.authResponse) {
                        location.reload();
                    } else {
                        console.log('User cancelled login or did not fully authorize.');
                    }
                }, {scope: 'user_photos'});
            }
        });
    }
    ;

    function getPhotos() {

        FB.api('/me?fields=id,name,photos', function (response) {
            var idData
            if (response) {
                for (var i = 0; i < response.photos.data.length; i++) {
                    if (i == 0) {
                        createActiveDiv(i);
                    }
                    else {
                        createDiv(i);
                    }
                    idData = response.photos.data[i];
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


    }
    ;

    function createActiveDiv(divId) {
        var div = document.createElement("div");
        div.setAttribute('id', divId);
        div.setAttribute('class', 'item active');
        document.getElementById("carouselWrapper").appendChild(div);
    }

    function createDiv(divId) {
        var div = document.createElement("div");
        div.setAttribute('id', divId);
        div.setAttribute('class', 'item');
        document.getElementById("carouselWrapper").appendChild(div);
    }
    ;

    function putImg(id, divId) {
        FB.api('/' + id + '?fields=images', function (response) {
            var img = document.createElement("img");
            img.src = response.images[1].source;
            img.width = 173;
            img.height = 130;
            img.alt = response.link;
            document.getElementById(divId).appendChild(img);
        });

    }
</script>
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">
    function getYID(e) {
        var elements = document.getElementsByClassName("bg-info");
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.remove("bg-info");
        }
        e.classList.add("bg-info");
        var id = e.getAttribute("data-id");
        var name = e.getAttribute("data-name");
        document.getElementById("videoname").value = name;
        document.getElementById("videoid").value = id;
        document.getElementById("buton").removeAttribute("disabled");
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $("div").on('click', '.panel-heading', function () {
            $(".panel-heading").stop(true, true);
            $(this).effect("highlight", {color: "#00FFE4"}, 300000);
        });
    });
</script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
