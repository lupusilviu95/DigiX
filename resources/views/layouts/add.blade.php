<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <link rel="shortcut icon" href="/images/favicon_i.ico">


    <title>DigiX</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"
          integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link href="/css/addfacebookfilecarousel.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/css/style.css">

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

</head>

<body id="app-layout">

@yield('facebook')

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
                <li><a href="/viewChest/{{$_SESSION['chest']}}/add/local">Local File</a></li>
                <li><a href="/viewChest/{{$_SESSION['chest']}}/add/facebook">Facebook</a></li>
                @if(Session::has('token') )
                    <li><a href="/viewChest/{{$_SESSION['chest']}}/add/youtube">Youtube</a></li>
                @else
                    <li><a href="/youtubeLogin/{{$id}}">Youtube</a></li>
                @endif
                @if(Session::has('sc_token') )
                    <li><a href="/viewChest/{{$_SESSION['chest']}}/add/soundcloud">Soundcloud</a></li>
                @else
                    <li><a href="/soundcloudLogin/{{$id}}">Soundcloud</a></li>
                @endif

                <li><a href="/viewChest/{{$_SESSION['chest']}}/add/slideshareSearch">Slideshare</a></li>
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

    function getSlideshareInfo(e) {
        var elements = document.getElementsByClassName("bg-info");
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.remove("bg-info");
        }
        e.classList.add("bg-info");
        var embed = e.getAttribute("data-embed");
        var title = e.getAttribute("data-title");
        var url = e.getAttribute("data-url");
        document.getElementById("slidesharename").value = title;
        document.getElementById("slideshareurl").value = url;
        document.getElementById("embedlink").value = embed;
        document.getElementById("buton").removeAttribute("disabled");
    }
    function getSoundcloudInfo(e) {
        var elements = document.getElementsByClassName("bg-info");
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.remove("bg-info");
        }
        e.classList.add("bg-info");
        var embed = e.getAttribute("data-embed");
        var title = e.getAttribute("data-title");
        var url = e.getAttribute("data-url");
        document.getElementById("songtitle").value = title;
        document.getElementById("embedurl").value = embed;
        document.getElementById("url").value = url;
        document.getElementById("buton").removeAttribute("disabled");
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $("div").on('click', '.panel-heading', function () {
            $(".panel-heading").stop(true, true);
            $(this).effect("highlight", {color: "#31B0D5"}, 300000);
        });
    });
</script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
