@extends('layouts.add')


@section('content')

<div class="container">
	   @if($videos)
			@foreach($videos as $video)
                      <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="panel panel-default">
                            
                                        <div class="panel-heading" onclick="getYID(this)" data-id="{{ $video['snippet']['resourceId']['videoId']}}" data-name="{{ $video['snippet']['title'] }}">
                                            <a href="http://youtube.com/watch?v={{ $video['snippet']['resourceId']['videoId']}}">
                                            {{ $video['snippet']['title'] }}
                                            </a>
                                        </div>
                                        <div class="panel-body embed-responsive embed-responsive-16by9" >
                                        <iframe class="embed responsive item" width="100%" height="100%" src="https://www.youtube.com/embed/{{ $video['snippet']['resourceId']['videoId']}}" frameborder="0" allowfullscreen></iframe>
                                        </div>
                    
                                </div>
                            </div>
                        </div>
                        
            @endforeach
            
            <div class="row">
                <div class="col-md-5 col-md-offset-3">
                <form action="/upload/youtube/{{$id}}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="chestid" value="{{$id}}">
                    <input type="hidden" name="videoid" id="videoid" value="">
                    <input type="hidden" name="videoname" id="videoname" values="">
                    <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                        <label for="tags">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="Enter tags separated by comma" value="{{ old('tags') }}">
                        @if ($errors->has('tags'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tags') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="rudenie">Relatives</label>
                        <select class="form-control" id="rudenie" name="rudenie">
                            <option>-none-</option>
                            <option>mama</option>
                            <option>tata</option>
                            <option>frate</option>
                            <option>sora</option>
                            <option>bunic</option>
                            <option>bunica</option>
                            <option>var</option>
                            <option>verisoara</option>
                            <option>unchi</option>
                            <option>matusa</option>
                        </select>
                    </div>
                        <button type="submit" class="btn btn-default" id="buton" disabled>Submit</button>
                    </form>
          </div>
        </div>

        @endif    

	
</div>	



@endsection