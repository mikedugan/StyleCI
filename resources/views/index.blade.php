@extends(Config::get('graham-campbell/core::layout'))

@section('title')
Landing
@stop

@section('content')
<header id="top" class="header">
    <div class="text-vertical-center">
        <h1>StyleCI</h1>
        <h3>Coding Style Continuous Integration Service</h3>
        <br>
        <a href="https://github.com/GrahamCampbell/StyleCI" class="btn btn-dark btn-lg">Find Out More</a>
    </div>
</header>
@stop

@section('css')
<style>
body {
    font-family: "Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;
}

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

.btn-dark {
    border-radius: 0;
    color: #fff;
    background-color: rgba(0,0,0,0.4);
}

.btn-dark:hover,
.btn-dark:focus,
.btn-dark:active {
    color: #fff;
    background-color: rgba(0,0,0,0.7);
}

.btn-light {
    border-radius: 0;
    color: #333;
    background-color: rgb(255,255,255);
}

.btn-light:hover,
.btn-light:focus,
.btn-light:active {
    color: #333;
    background-color: rgba(255,255,255,0.8);
}

.header {
	padding-top: 100px;
    display: table;
    position: relative;
    width: 100%;
}
</style>
@stop
