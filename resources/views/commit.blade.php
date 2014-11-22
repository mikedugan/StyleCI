@extends(Config::get('graham-campbell/core::layout'))

@section('title')
Commit - {{ $commit->message }}
@stop

@section('top')
<div class="page-header">
<h1>Commit Analysis</h1>
<h2>{{ $commit->message }}</h2>
<h4>{{ $commit->id }}</h4>
</div>
@stop

@section('content')
<p class="lead">Status: {{ $commit->summary() }}</p>
<hr>
@stop
