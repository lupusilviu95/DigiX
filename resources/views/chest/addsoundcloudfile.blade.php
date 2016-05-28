@extends('layouts.add')


@section('content')

    <div class="container">
        @if($songs)
            @foreach($songs as $song)
                <div class="row">
                    <div class="col-md-7 col-md-offset-2">
                        <div class="panel panel-default">

                            <div class="panel-heading" onclick="getSoundcloudInfo(this)" data-title="{{$song->title}}"
                                 data-url="{{$song->url}}" data-embed="{{$song->embedurl}}">
                                <a href="{{$song->url}}">
                                    {{ $song->title }}
                                </a>
                            </div>
                            <div class="panel-body embed-responsive embed-responsive-16by9">
                                <iframe width="100%" height="166" scrolling="no" frameborder="no"
                                        src="https://w.soundcloud.com/player/?url={{$song->embedurl}}&amp;color=ff6600&amp;auto_play=false&amp;show_artwork=true"></iframe>

                            </div>

                        </div>
                    </div>
                </div>

            @endforeach

            <div class="row">
                <div class="col-md-5 col-md-offset-3">
                    <form action="/upload/soundcloud/{{$id}}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="chestid" value="{{$id}}">
                        <input type="hidden" name="songtitle" id="songtitle" value="">
                        <input type="hidden" name="embedurl" id="embedurl" value="">
                        <input type="hidden" name="url" id="url" value="">
                        <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                            <label for="tags">Tags</label>
                            <input type="text" class="form-control" id="tags" name="tags"
                                   placeholder="Enter tags separated by comma" value="{{ old('tags') }}">
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