@extends(Config::get('graham-campbell/core::layout'))

@section('title')
{{ $repo->name }}
@stop

@section('top')
<div class="page-header">
<h1>{{ $repo->name }}</h1>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-xs-8">
        <p class="lead">
            @if (count($commits) == 0)
                We haven't analysed anything yet.
            @else
                Here you can see all the analysed commits:
            @endif
        </p>
    </div>
</div>
@foreach($commits as $commit)
    <h3>{{ $commit->message }}</h3>
        @if ($commit->combinedStatus() === 1)
        <p style="color:green">
        @elseif ($commit->combinedStatus() === 2)
        <p style="color:red">
        @else
        <p>
        @endif
            <strong>{{ $commit->summary() }}</strong>
        </p>
    <p><a class="btn btn-success" href="{{ asset('commits/'.$commit->id) }}"><i class="fa fa-rocket"></i> Show Details</a></p>
    <br>
@endforeach
@stop
