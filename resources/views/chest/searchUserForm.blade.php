@extends('layouts.add')


@section('content')



    <div class="container">
        @if(Session::has('flash_error'))
            <div class="alert alert-warning">{{Session::get('flash_error')}}</div>
        @endif
        <div class="row">
            <div class="col-md-5 col-md-offset-3">
                <form action="/search/processSlideshare/{{$id}}" method="GET">
                    {!! csrf_field() !!}
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label for="user">SlideShare Username</label>
                        <input type="text" class="form-control" id="usernameId" name="username"
                               placeholder="SlideShare Username">
                        @if ($errors->has('username'))
                            <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                      </span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-default">Submit</button>

                </form>
            </div>
        </div>
    </div>

@endsection