<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DigiX</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"
          integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <link rel="stylesheet" href="/css/bootstrap-social.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap3_player.css" rel="stylesheet">


    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <link rel="stylesheet" href="/css/dashboard_chest_Style.css">
    <link rel="shortcut icon" href="/images/favicon_i.ico">

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


                <li><a href="/viewChest/{{$_SESSION['currChest']}}/add">Add File</a></li>
            </ul>

            <div class="col-sm-3 col-md-3 ">
                <form name="searchform" class="navbar-form" role="search"
                      action="/viewChest/{{$_SESSION['currChest']}}/search" method="GET"
                      onsubmit="return validateForm()">
                    <input type="hidden" name="chestid" value="{{$_SESSION['currChest']}}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">

                <li>
                    <div class="col-sm-3 col-md-3">
                        <!-- Single button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Sort <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                                <li><a href="?sortOption=name">Name</a></li>
                                <li><a href="?sortOption=type">Type</a></li>
                                <li><a href="?sortOption=created_at">Created at</a></li>

                            </ul>
                        </div>
                    </div>
                </li>

                <li>
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
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

<script type="text/javascript">


    $(document).ready(function () {
        $(".dropdown-toggle").dropdown();
        $('#myTable').on('click', '.clickable-row', function (event) {
            $(this).addClass('bg-info').siblings().removeClass('bg-info');

        });


    });


</script>
<script type="text/javascript">
    function pop(e) {
        var urldelete = "/delete/file/";
        var urldownload = "/download/file/"
        var id = e.getAttribute("id");
        var sterge = urldelete.concat(id);
        var descarca = urldownload.concat(id);
        document.getElementById("deleteOption").href = sterge;
        document.getElementById("downloadOption").href = descarca;


    }
</script>
<script type="text/javascript">
    function validateForm() {
        var x = document.forms["searchform"]["srch-term"].value;

        if (/^(([a-z]+)([;][a-z]+)?)$|^(([a-z]+)[,]([a-z]+)([;][a-z]+)?)$|^(([a-z]+)[,]([a-z]+)[,]([a-z]+)([;][a-z]+)?)$/i.test(x)) {
            return true;
        }
        else {
            alert("Search query is malformed; it should be something like : tag,tag;relative");
            return false;
        }
    }

</script>

<script>
    function confirmDelete(elem) {
        var sterge = elem.getAttribute("href");
        elem.href = "";
        if (sterge != "") {
            var conf = confirm("Are you sure you want to delete the file?");
            if (conf == true) {
                elem.href = sterge;
            }
        }
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="/js/bootstrap3_player.js"></script>

</body>
</html>
