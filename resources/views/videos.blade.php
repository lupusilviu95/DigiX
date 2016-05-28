@extends('layouts.dasboard')



@section('content')

    <ul class="list-unstyled video-list-thumbs row">
        @foreach($videos as $video)
            <li class="col-lg-3 col-sm-4 col-xs-6">
                <a href="http://youtube.com/watch?v={{ $video['snippet']['resourceId']['videoId']}}"
                   title="{{ $video['snippet']['title'] }}" target="_blank">
                    <img src="{{ $video['snippet']['thumbnails']['medium']['url'] }}"
                         alt="{{ $video['snippet']['title'] }}"/>
                    <h2 class="truncate">{{ $video['snippet']['title'] }}</h2>
                </a>
            </li>
        @endforeach
    </ul>



@endsection