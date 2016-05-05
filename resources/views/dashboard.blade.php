@extends('layouts.dasboard')

@section('content')
<div class="container">

                @if($cufere!=null)
                    @foreach($cufere as $cufar)
                      <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="panel panel-default">
                            
                                        <div class="panel-heading" onclick="pop(this)" id="{{$cufar->id_cufar}}">
                                            <a href="/viewChest/{{$cufar->id_cufar}}">
                                                {{$cufar->name}}({{$cufar->freeSlots}}/{{$cufar->capacity}})
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



