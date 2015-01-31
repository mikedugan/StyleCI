@extends(Config::get('core.default'))

@section('title')
Landing
@stop

@section('content')
<header id="top" class="header">
    <div class="text-vertical-center">
        <h1>StyleCI</h1>
        <h3>Coding Style Continuous Integration Service</h3>
        <br>
        <a href="{{ asset('repos') }}" class="btn btn-dark btn-lg">Get Started</a>
    </div>
</header>
@stop

@section('css')
<style>

.text-vertical-center {
    display: table-cell;
    text-align: center;
    vertical-align: middle;
}

.text-vertical-center h1 {
    margin: 0;
    padding: 0;
    font-size: 4.5em;
    font-weight: 700;
}

.header {
    padding-top: 100px;
    display: table;
    position: relative;
    width: 100%;
}
</style>
@stop
