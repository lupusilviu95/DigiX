@extends('layouts.dasboard')
    <?php 
    function name_cmp($a,$b){
        return strcmp(strtolower($a->name),strtolower($b->name));
    }
    function capacity_cmp($a,$b){
        return ($a->capacity>$b->capacity);
    }
     function date_cmp($a,$b){
      return strtotime($b->createdat)-strtotime($a->createdat);
    }
    ?>
@section('content')

<div class="container">
 @if(Session::has('flash_notice'))
        <div class="alert alert-success">{{Session::get('flash_notice')}}</div>
    @endif
                @if($cufere!=null)
                    <?php 
                    if(isset($_GET['sortOption'])){
                        $opt=$_GET['sortOption'];
                        if($opt=="name")
                            usort($cufere,"name_cmp");
                        if($opt=="capacity")
                            usort($cufere,"capacity_cmp");
                        if($opt=="created_at")
                          usort($cufere,"date_cmp");
                    }
                    ?>
                    @foreach($cufere as $cufar)
                      <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="panel panel-default">
                            
                                        <div class="panel-heading" onclick="pop(this)" id="{{$cufar->id_cufar}}">
                                            <a href="/viewChest/{{$cufar->id_cufar}}">
                                                {{$cufar->name}}({{$cufar->getFreeSlots()}}/{{$cufar->capacity}})
                                            </a>
                                        </div>
                                        <div class="panel-body">
                                        {{$cufar->description}}
                                        </div>
                    
                                </div>
                            </div>
                        </div>
                        
                    @endforeach 
                   
                @else 
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="panel panel-default">
                                <div class="panel-heading">Nu ai nici un cufar :(</div>
                            </div>
                        </div>
                    </div>
                @endif
</div>
@endsection



