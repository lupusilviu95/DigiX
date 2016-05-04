@extends('layouts.app')

@section('content')

<script src="/js/slider.js"></script>
 <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
 <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
 <script src="resources/scripts/jquery-1.7.1.min.js"></script>
    <script src="resources/scripts/jquery-ui-1.8.10.custom.min.js"></script>
    <script src="resources/scripts/axure/axQuery.js"></script>
    <script src="resources/scripts/axure/globals.js"></script>
    <script src="resources/scripts/axutils.js"></script>
    <script src="resources/scripts/axure/annotation.js"></script>
    <script src="resources/scripts/axure/axQuery.std.js"></script>
    <script src="resources/scripts/axure/doc.js"></script>
    <script src="data/document.js"></script>
    <script src="resources/scripts/messagecenter.js"></script>
    <script src="resources/scripts/axure/events.js"></script>
    <script src="resources/scripts/axure/action.js"></script>
    <script src="resources/scripts/axure/expr.js"></script>
    <script src="resources/scripts/axure/geometry.js"></script>
    <script src="resources/scripts/axure/flyout.js"></script>
    <script src="resources/scripts/axure/ie.js"></script>
    <script src="resources/scripts/axure/model.js"></script>
    <script src="resources/scripts/axure/repeater.js"></script>
    <script src="resources/scripts/axure/sto.js"></script>
    <script src="resources/scripts/axure/utils.temp.js"></script>
    <script src="resources/scripts/axure/variables.js"></script>
    <script src="resources/scripts/axure/drag.js"></script>
    <script src="resources/scripts/axure/move.js"></script>
    <script src="resources/scripts/axure/visibility.js"></script>
    <script src="resources/scripts/axure/style.js"></script>
    <script src="resources/scripts/axure/adaptive.js"></script>
    <script src="resources/scripts/axure/tree.js"></script>
    <script src="resources/scripts/axure/init.temp.js"></script>
    <script src="files/home/data.js"></script>
    <script src="resources/scripts/axure/legacy.js"></script>
    <script src="resources/scripts/axure/viewer.js"></script>
    <script type="text/javascript">
      $axure.utils.getTransparentGifPath = function() { return 'resources/images/transparent.gif'; };
      $axure.utils.getOtherPath = function() { return 'resources/Other.html'; };
      $axure.utils.getReloadPath = function() { return 'resources/reload.html'; };
    </script>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                
                    <div id="slider">
                        <ul class="slides">
                            <li class="slide"><img src="/css/photo1.jpg"></li>
                            <li class="slide"><img src="/css/photo2.jpg"></li>
                            <li class="slide"><img src="/css/photo3.jpg"></li>
                            <li class="slide"><img src="/css/photo4.jpg"></li>
                            <li class="slide"><img src="/css/photo1.jpg"></li>                             
                        </ul>
                    </div>

                    <script src="../js/slider.js"></script>
                    <!-- Youtube (Inline Frame) -->
                    <div id="u52" class="ax_inline_frame" data-label="Youtube">
                         <iframe id="u52_input" data-label="Youtube" scrolling="auto" frameborder="1"></iframe>
                    </div>

                    <!-- SoundCloud (Inline Frame) -->
                     <div id="u61" class="ax_inline_frame" data-label="SoundCloud">
                         <iframe id="u61_input" data-label="SoundCloud" scrolling="auto" frameborder="0"></iframe>
                     </div>

                     <!-- Tweet PrintScreen (Image) -->
                     <div id="u62" class="ax_image" data-label="Tweet PrintScreen">
                            <img id="u62_img" class="img " src="images/home/tweet_printscreen_u62.jpg"/>
                        <!-- Unnamed () -->
                        <div id="u63" class="text">
                        <p><span></span></p>
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
