<!DOCTYPE html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{{ Config::get('core.name') }} - @section('title')
@show</title>
@include('partials.header')
</head>
<body>
<div id="wrap">
@include('partials.navbar')
@section('top')
@show
@include('partials.notifications')
<div class="container">
@section('content')
@show
@include('partials.footer')
@section('bottom')
@show
</body>
</html>
