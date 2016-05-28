@extends('layouts.chest')
<?php
function name_cmp($a, $b)
{
    return strcmp(strtolower($a->name), strtolower($b->name));
}
function type_cmp($a, $b)
{
    return ($a->type > $b->type);
}
function date_cmp($a, $b)
{
    return strtotime($b->createdat) - strtotime($a->createdat);
}
?>
@section('content')
    <div class="container">
        @if(Session::has('flash_info'))
            <div class="alert alert-info">{{Session::get('flash_info')}}</div>
        @endif
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    @if($files!=null)
                        <?php
                        if (isset($_GET['sortOption'])) {
                            $opt = $_GET['sortOption'];
                            if ($opt == "name")
                                usort($files, "name_cmp");
                            if ($opt == "type")
                                usort($files, "type_cmp");
                            if ($opt == "created_at")
                                usort($files, "date_cmp");
                        }
                        ?>
                        <table class="table table-bordered" id="myTable">
                            <tbody>
                            <tr>
                                <th><a href="?sortOption=name"> File name</a></th>
                                <th><a href="?sortOption=type">Type</a></th>
                                <th><a href="?sortOption=created_at">Created at</a></th>
                                <th>Twitter</th>
                                <th>Source</th>
                            </tr>
                            @foreach($files as $file)
                                <tr class="clickable-row" id="{{$file->fileid}}" onclick="pop(this)">
                                    <td><a href="/viewFile/{{$file->fileid}}">{{$file->name}}</a></td>
                                    <td>{{$file->type}}</td>
                                    <td>{{$file->createdat}}</td>
                                    @if($file->type=='jpg')
                                        <td><a href="/tweet/{{$file->fileid}}">Tweet this</a></td>
                                    @else
                                        <td>N/A</td>
                                    @endif
                                    <td>{{$file->origin}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="panel-heading">Cufar gol</div>
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection