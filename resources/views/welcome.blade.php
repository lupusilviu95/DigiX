@extends('layouts.app')

@section('content')

<script src="/js/carousel.js"></script>
 <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
 <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <style>
        body{
            font-family: 'Lato';
            background: url("/css/treasure_top.jpg") no-repeat, url("/css/treasure_background.jpg") no-repeat,url("/css/treasure_login.jpg") no-repeat;
            -webkit-background-size: auto,auto,auto;
            -moz-background-size: auto,auto,auto;
            -o-background-size: auto,auto,auto;
            background-size: auto,auto,auto;
            background-position: 0px 51px,0px 380px,bottom;
            }

            footer {
            margin-top: 20px;
            margin-bottom: 300px;
            }
</style>

    <body>
    <div class="container">
    <div class="row">   
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">                 

                    
            <div class="row">
            <div class="col-xs-9 col-sm-offset-1">
                    
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">

                <div class="item active">
                    <img src="/css/photo1.jpg" alt="Responsive image"class="img-responsive"  >
                    
                </div>

                <div class="item">
                    <img src="/css/photo2.jpg" alt="Responsive image"class="img-responsive"  >
                    
                </div>
    
                <div class="item">
                    <img src="/css/photo3.jpg" alt="Responsive image"class="img-responsive"  >
                    
                </div>

                <div class="item">
                    <img src="/css/photo4.jpg" alt="Responsive image"class="img-responsive"  >
                </div>

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


            <script src="../js/carousel.js"></script>
                    
    
            <div class="videoWrapper">
                <iframe class="embed-responsive-item"  src="https://www.youtube.com/embed/N9q9uNa4DAg" frameborder="0" width="560" height="315"></iframe>
            </div>


            <div class="audioWrapper">
                <iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/4152184&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
            </div>

            <div class="twitterWrapper">
                <blockquote class="twitter-tweet" data-lang="en"><p lang="en" dir="ltr">The cute city of Bellagio, Italy. Image via <a href="https://twitter.com/AprendizViajant">@AprendizViajant</a> on Instagram. Thanks for tagging <a href="https://twitter.com/hashtag/TravelDudes?src=hash">#TravelDudes</a>! <a href="https://twitter.com/hashtag/Travel?src=hash">#Travel</a> <a href="https://t.co/VhRkpBxlcU">pic.twitter.com/VhRkpBxlcU</a></p>&mdash; Melvin (@TravelDudes) <a href="https://twitter.com/TravelDudes/status/729827106172096512">May 10, 2016</a></blockquote>
                <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
           </div>
           </div>
           </div>
        </div>
        </div>
    </div>
</div>

<footer class="footer">
      <div class="container">
        <p class="text-muted"></p>
      </div>
    </footer>

</body>


@endsection
