@extends(Config::get('graham-campbell/core::email'))

@section('content')
<p>The coding style analysis of your commit "{{ $commit }}" on repo "{{ $repo }}" relieved problems.
<p>Click <a href="{{ $link }}">here</a> to see the details.</p>
@stop
