<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="@yield('description', 'StyleCI Is A PHP Coding Style Continuous Integration Service')">
<meta name="author" content="Graham Campbell">

<meta name="token" content="{{ csrf_token() }}">

<meta content="summary" name="twitter:card" />
<meta content="@yield('title', 'StyleCI')" name="twitter:title" />
<meta content="@yield('description', 'StyleCI Is A PHP Coding Style Continuous Integration Service')" name="twitter:description" />
<meta content="{{ url('img/styleci-og.png') }}" name="twitter:image:src" />
<meta content="StyleCI" property="og:site_name" />
<meta content="object" property="og:type" />
<meta content="{{ url('img/styleci-og.png') }}" property="og:image" />
<meta content="@yield('title', 'StyleCI')" property="og:title" />
<meta content="{{ $currentUrl }}" property="og:url" />
<meta content="@yield('description', 'StyleCI Is A PHP Coding Style Continuous Integration Service')" property="og:description" />

<link href="//fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ elixir('dist/css/app.css') }}">

@section('css')
@show

<!--[if lt IE 9]>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<link rel="shortcut icon" href="{!! asset('favicon.ico') !!}">
