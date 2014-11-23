@extends(Config::get('graham-campbell/core::layout'))

@section('title')
Repos
@stop

@section('top')
<div class="page-header">
<h1>Analysed Repos</h1>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-xs-8">
        <p class="lead">
            @if (count($repos) == 0)
                We haven't analysed anything yet.
            @else
                Here you can see all our analysed repos:
            @endif
        </p>
    </div>
</div>
@foreach($repos as $repo)
    <h3>{{ $repo->name }}</h3>
    @if ($commit = $repo->commits()->where('ref', 'refs/heads/master')->first())
        @if ($commit->combinedStatus() === 1)
        <p style="color:green">
        @elseif ($commit->combinedStatus() === 2)
        <p style="color:red">
        @else
        <p>
        @endif
            <strong>{{ $commit->summary() }}</strong>
        </p>
        <p><a class="btn btn-success" href="{{ asset('repos/'.$repo->id) }}"><i class="fa fa-rocket"></i> Show Details</a></p>
    @else
        <p><strong>No commits have been pushed to the master yet.</strong></p>
    @endif
    <br>
@endforeach
@stop
