@extends(Config::get('graham-campbell/core::layout'))

@section('title')
{{ $repo->name }}
@stop

@section('top')
<div class="page-heading">
    <div class="container">
        <h1>{{ $repo->name }}</h1>
        <p>Here you can see all the analysed commits</p>
    </div>
</div>
@stop

@section('content')
@if($commits->count() > 0)
<div class="repo-table">
    <div class="row hidden-xs">
        <div class="col-sm-6">
            <strong>Commit</strong>
        </div>
        <div class="col-sm-1">
            <strong>Time</strong>
        </div>
        <div class="col-sm-1">
            <strong>Status</strong>
        </div>
        <div class="col-sm-4">

        </div>
    </div>
    @foreach($commits as $commit)
    <div class="row @if($commit->status() === 1) bg-success @elseif ($commit->status() === 2) bg-danger @else bg-active @endif">
        <div class="col-sm-6">
            <strong>{{ $commit->message }}</strong>
            <br>
            <small>{{ $commit->created_at->diffForHumans() }}</small>
        </div>
        <div class="col-sm-1">
            <small>{{ $commit->excecutedTime() }}</small>
        </div>
        <div class="col-sm-1">
            @if ($commit->status() === 1)
            <p style="color:green">
            @elseif ($commit->status() === 2)
            <p style="color:red">
            @else
            <p style="color:grey">
            @endif
            <strong>{{ $commit->summary() }}</strong>
            </p>
        </div>
        <div class="col-sm-4 repo-buttons">
            <a class="badge-id" href="https://github.com/{{ $repo->name }}/commit/{{ $commit->id }}">
                {{ $commit->shorthandId() }}
            </a>
            <a class="btn btn-sm btn-default" href="{{ route('commit_path', $commit->id) }}">Show Details</a>
        </div>
    </div>
    @endforeach
</div>
@else
<p class="lead">We haven't analysed anything yet.</p>
@endif
@stop
