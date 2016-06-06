@extends('layouts.add')


@section('content')


    <div class="container">
        <div class="row">
            <div class="col-md-5 col-md-offset-3">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="pill" href="#local">Local File</a></li>
                    <li><a data-toggle="pill" href="#facebook" onclick="authenticate()">Facebook</a></li>

                    @if( Session::has('token') )
                        <li><a data-toggle="pill" href="#youtube">Youtube</a></li>
                    @else
                        <li><a href="/loginY/{{$id}}">Youtube</a></li>
                    @endif


                    <li><a data-toggle="pill" href="#soundcloud">SoundCloud</a></li>
                </ul>

                <div class="tab-content">
                    <div id="local" class="tab-pane fade in active">
                        <h3>Local File</h3>
                        <form action="/upload/{{$id}}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <input type="hidden" name="chestid" value="{{$id}}">
                            <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                                <label for="file">File input</label>
                                <input type="file" id="file" name="file">

                                @if ($errors->has('file'))

                                    <span class="help-block">
						<strong>{{ $errors->first('file') }}</strong>
						</span>
                                @endif
                            </div>
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
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>

                    <div id="facebook" class="tab-pane fade">
                        <h3>Facebook photo</h3>
                        <form action="/upload/{{$id}}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="chestid" value="{{$id}}">
                            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">

                                <!-- Wrapper for slides -->
                                <div id="carouselWrapper" class="carousel-inner" role="listbox">

                                </div>

                                <!-- Left and right controls -->
                                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>

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

                            <button type="submit" class="btn btn-default" disabled>Submit</button>

                        </form>
                    </div>

                    <div id="youtube" class="tab-pane fade">
                        <h3>Youtube video</h3>
                    <!-- @if(Session::has('videos'))
                        {{$videos=Session::get('videos')}}
                    @endif -->
                        @if(isset($videos))
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
                        @endif
                    </div>

                    <div id="soundcloud" class="tab-pane fade">
                        <h3>Soundcloud audio</h3>
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                            commodo consequat.</p>
                    </div>

                </div>
            </div>
        </div>
@endsection