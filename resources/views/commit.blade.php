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
@if ($commit->combinedStatus() === 1)
<p class="lead" style="color:green">
@elseif ($commit->combinedStatus() === 2)
<p class="lead" style="color:red">
@else
<p class="lead" style="color:grey">
@endif
    Status: {{ $commit->summary() }}
</p>
<hr>
@stop
