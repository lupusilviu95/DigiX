@extends('layouts.chest')
<?php
function name_cmp($a, $b)
{
    return strcmp(strtolower($a->name), strtolower($b->name));
}
function type_cmp($a, $b)
{
    return ($a->type > $b->type);
}
function date_cmp($a, $b)
{
    return strtotime($b->createdat) - strtotime($a->createdat);
}
?>
@section('content')
    <div class="container">
        @if(Session::has('flash_info'))
            <div class="alert alert-info">{{Session::get('flash_info')}}</div>
        @endif
     
        
                    @if($files!=null)
                        <?php
                        if (isset($_GET['sortOption'])) {
                            $opt = $_GET['sortOption'];
                            if ($opt == "name")
                                usort($files, "name_cmp");
                            if ($opt == "type")
                                usort($files, "type_cmp");
                            if ($opt == "created_at")
                                usort($files, "date_cmp");
                        }
                        ?>
                
                        @foreach($files as $file)
                           @if($file->type=='youtube')
                                 <div class="row">
                                    <div class="col-md-7 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <a href="{{$file->origin}}">{{$file->name}}</a>
                                                <span class="pull-right">
                                                                           <ul class="list-inline">
                                                                                 <li><a href="/delete/file/{{$file->fileid}}" onclick="confirmDelete(this)"><i class="glyphicon glyphicon-trash"></i></a></li>
                                                                            </ul>
                                                                       </span>
                                            </div>
                                             <div class="panel-body embed-responsive embed-responsive-16by9">
                                                 <iframe class="embed responsive item" width="100%" height="100%"
                                                 src="https://www.youtube.com/embed/{{$file->path}}"
                                                 frameborder="0" allowfullscreen>
                                                 </iframe>
                                            </div>
                                            <div class="panel-footer">
                                                Tags : {{$file->getFormatedTags()}}
                                                <span class="pull-right">
                                                    Relative : {{$file->getFormatedRelative()}}
                                                </span>
                                            </div>
                                    </div>
                                </div> 
                            </div>       
                            @elseif($file->type=='facebook')
                             <div class="row">
                                    <div class="col-md-7 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$file->name}}
                                            <span class="pull-right">
                                                                           <ul class="list-inline">
                                                                                 <li><a href="/delete/file/{{$file->fileid}}" onclick="confirmDelete(this)"><i class="glyphicon glyphicon-trash"></i></a></li>
                                                                            </ul>
                                                                       </span>
                                            </div>
                                             <div class="panel-body">
                                                 <img class="img-responsive" src="{{$file->path}}" alt="{{$file->name}}">
                                            </div>
                                            <div class="panel-footer">
                                                Tags : {{$file->getFormatedTags()}}
                                                <span class="pull-right">
                                                    Relative : {{$file->getFormatedRelative()}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>        

                            @elseif($file->type=='soundcloud')
                            <div class="row">
                             
                                    <div class="col-md-7 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <a href="{{$file->origin}}">{{$file->name}}</a>
                                                <span class="pull-right">
                                                                           <ul class="list-inline">
                                                                                 <li><a href="/delete/file/{{$file->fileid}}" onclick="confirmDelete(this)"><i class="glyphicon glyphicon-trash"></i></a></li>
                                                                            </ul>
                                                                       </span>
                                            </div>
                                             <div class="panel-body embed-responsive embed-responsive-16by9" style="max-height: 166px;" >
                                                  <iframe width="100%" height="100%" scrolling="no" frameborder="no"
                                                  src="https://w.soundcloud.com/player/?url={{$file->origin}}&amp;color=ff6600&amp;auto_play=false&amp;show_artwork=true">
                                                  </iframe>
                                            </div>
                                            <div class="panel-footer">
                                                Tags : {{$file->getFormatedTags()}}
                                                <span class="pull-right">
                                                    Relative : {{$file->getFormatedRelative()}}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>        

                            @elseif($file->type=='slideshare')
                            <div class="row">
                                    <div class="col-md-7 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <a href="{{$file->origin}}">{{$file->name}}</a>
                                                <span class="pull-right">
                                                                           <ul class="list-inline">
                                                                                 <li><a href="/delete/file/{{$file->fileid}}" onclick="confirmDelete(this)"><i class="glyphicon glyphicon-trash"></i></a></li>
                                                                            </ul>
                                                                       </span>
                                            </div>
                                             <div class="panel-body embed-responsive embed-responsive-16by9">
                                                 <iframe class="embed responsive item" width="100%" height="100%"
                                                 src="{{$file->path}}"
                                                 frameborder="0" allowfullscreen>
                                                 </iframe>
                                            </div>
                                            <div class="panel-footer">
                                                Tags : {{$file->getFormatedTags()}}
                                                <span class="pull-right">
                                                    Relative : {{$file->getFormatedRelative()}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>        

                            @elseif($file->type=='jpg' || $file->type=='png') 

                             <div class="row">
                                    <div class="col-md-7 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$file->name}}
                                                                       <span class="pull-right">
                                                                           <ul class="list-inline">
                                                                                 <li><a href="/download/file/{{$file->fileid}}" ><i class="glyphicon glyphicon-download-alt"></i></a></li>
                                                                                 <li><a href="/delete/file/{{$file->fileid}}" onclick="confirmDelete(this)"><i class="glyphicon glyphicon-trash"></i></a></li>
                                                                                 <li ><a href="/tweet/{{$file->fileid}}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                                                            </ul>
                                                                       </span>
                                            </div>
                                             <div class="panel-body">
                                                 <img class="img-responsive" src="/{{$file->path}}" alt="{{$file->name}}">
                                            </div>
                                             <div class="panel-footer">
                                                Tags : {{$file->getFormatedTags()}}
                                                <span class="pull-right">
                                                    Relative : {{$file->getFormatedRelative()}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>        
                            @elseif($file->type=='mp3') 
                            <div class="row">  
                            <div class="col-md-7 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">{{$file->name}}
                                                                       <span class="pull-right">
                                                                           <ul class="list-inline">
                                                                                 <li><a href="/download/file/{{$file->fileid}}" ><i class="glyphicon glyphicon-download-alt"></i></a></li>
                                                                                 <li><a href="/delete/file/{{$file->fileid}}" onclick="confirmDelete(this)"><i class="glyphicon glyphicon-trash"></i></a></li>
                                                                            </ul>
                                                                       </span>
                                            </div>
                                             <div class="panel-body">
                                                  <audio controls>
                                                      <source src="/{{$file->path}}" type="audio/mpeg" />
                                                      An html5-capable browser is required to play this audio. 
                                                  </audio>
                                            </div>
                                             <div class="panel-footer">
                                                Tags : {{$file->getFormatedTags()}}
                                                <span class="pull-right">
                                                    Relative : {{$file->getFormatedRelative()}}
                                                </span>
                                            </div>
                                        </div>
                                    </div> 
                            </div>
                            @else 
                            <div class="row">
                                <div class="col-md-7 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading"><a href="/viewFile/{{$file->fileid}}">{{$file->name}}</a>
                                                                       <span class="pull-right">
                                                                           <ul class="list-inline">
                                                                                 <li><a href="/download/file/{{$file->fileid}}" ><i class="glyphicon glyphicon-download-alt"></i></a></li>
                                                                                 <li><a href="/delete/file/{{$file->fileid}}" onclick="confirmDelete(this)"><i class="glyphicon glyphicon-trash"></i></a></li>
                                                                            </ul>
                                                                       </span>
                                            </div>
                                             <div class="panel-body">
                                             <p class="text-center"><em>No preview available</em></p>
                                            </div>
                                             <div class="panel-footer">
                                                Tags : {{$file->getFormatedTags()}}
                                                <span class="pull-right">
                                                    Relative : {{$file->getFormatedRelative()}}
                                                </span>
                                            </div>
                                        </div>
                                    </div> 
                            </div>
                            @endif

                        @endforeach
                    @else
                        <div class="panel-heading">Cufar gol</div>
                    @endif

                
            </div>
    


@endsection