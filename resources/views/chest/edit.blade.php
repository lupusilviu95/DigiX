@extends('layouts.app')


@section('content')
    <div class="container">
        <form action="/update/chest/{{$cufar->id_cufar}}" method="POST">
        {{method_field('PATCH')}}
        {!! csrf_field() !!}

        <!-- <input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> -->
            <div class="form-group{{ $errors->has('chestName') ? ' has-error' : '' }}">
                <label for="chestName">Chest Name</label>
                <input type="text" class="form-control" id="chestName" name="chestName"
                       placeholder="Your new chest name" value="{{$cufar->name}}">
                @if ($errors->has('chestName'))
                    <span class="help-block">
			<strong>{{ $errors->first('chestName') }}</strong>
		</span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description">Description</label>
                <textarea rows="3" class="form-control" id="description" name="description"
                          placeholder="Brief description ..">{{$cufar->description}}</textarea>
                @if ($errors->has('description'))
                    <span class="help-block">
			<strong>{{ $errors->first('description') }}</strong>
		</span>
                @endif
            </div>
            <button type="submit" class="btn btn-default">Update chest</button>

        </form>
    </div>


@endsection