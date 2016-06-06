@extends('layouts.add')


@section('content')

    <div class="container">
        @if($slides)
            @foreach($slides as $slideshow)
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-default">

                            <div class="panel-heading" onclick="getSlideshareInfo(this)"
                                 data-embed="{{$slideshow->embedlink}}" data-title="{{ $slideshow->title }}"
                                 data-url="{{$slideshow->url}}">
                                <a href="{{$slideshow->url}}">
                                    {{ $slideshow->title }}
                                </a>
                            </div>
                            <div class="panel-body embed-responsive embed-responsive-16by9">
                                <iframe src="{{$slideshow->embedlink}}" class="embed responsive item" allowfullscreen></iframe>
                            </div>

                        </div>
                    </div>
                </div>

            @endforeach



            <div class="row">
                <div class="col-md-5 col-md-offset-3">
                    <form action="/upload/slideshare/{{$id}}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="chestid" value="{{$id}}">
                        <input type="hidden" name="embedlink" id="embedlink" value="">
                        <input type="hidden" name="slidesharename" id="slidesharename" value="">
                        <input type="hidden" name="slideshareurl" id="slideshareurl" value="">
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
                                <option>mother</option>
                                <option>father</option>
                                <option>brother</option>
                                <option>sister</option>
                                <option>grandfather</option>
                                <option>garndmother</option>
                                <option>cousin</option>
                                <option>uncle</option>
                                <option>aunt</option>
                                </select>
                        </div>

                        <button type="submit" class="btn btn-default" id="buton" disabled>Submit</button>

                    </form>
                </div>
            </div>
          


        @endif
    </div>

@endsection