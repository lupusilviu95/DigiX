@extends('layouts.dasboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                @if($cufere!=null)
                    @foreach($cufere as $cufar)
                        <div class="panel-heading"><a href="/viewChest/{{$cufar->id_cufar}}">{{$cufar->name}}</a></div>
                            <div class="panel-body">
                                {{$cufar->description}}
                           </div>

                        <hr>
                        <br>
                    @endforeach 
                   
                @else 
                    <div class="panel-heading">Nu ai nici un cufar :(</div>
                @endif
                           
            </div>
        </div>
    </div>
</div>
@endsection



