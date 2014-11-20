<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="StyleCI Is A PHP Coding Style Continuous Integration Service">
<meta name="author" content="Graham Campbell">

{!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css') !!}
{!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css') !!}
{!! HTML::style('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css') !!}
{!! Asset::styles('main') !!}
@section('css')
@show

<!--[if lt IE 9]>
  {!! HTML::script('//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js') !!}
  {!! HTML::script('//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js') !!}
<![endif]-->

<link rel="shortcut icon" href="{!! asset('favicon.ico') !!}">
