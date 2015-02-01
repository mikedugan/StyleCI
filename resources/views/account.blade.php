@extends(Config::get('core.default'))

@section('title')
Your Account
@stop

@section('top')
<div class="page-heading">
    <div class="container">
        <h1>Your Account</h1>
        <p>Here you can manage your StyleCI account.</p>
    </div>
</div>
@stop

@section('content')
<h2>Notifications</h2>
<p class="lead">TODO</p>
<hr>
<h2>Repositories</h2>
@forelse($repos as $id => $repo)
<div class="row">
    <div class="col-sm-12">
        <h4>{{ $repo['name'] }}</h4>
        @if($repo['enabled'])
        <h5>StyleCI is currently enabled on this repo.</h5>
        <a class="btn btn-primary" href="{{ route('repo_path', $id) }}"><i class="fa fa-history"></i> Show Commits</a> <a class="btn btn-danger" href="{{ route('disable_repo_path', $id) }}"><i class="fa fa-times"></i> Disable StyleCI</a>
        @else
        <h5>StyleCI is currently disabled on this repo.</h5>
        <a class="btn btn-success" href="{{ route('enable_repo_path', $id) }}"><i class="fa fa-check"></i> Enable StyleCI</a>
        @endif
    </div>
</div>
@empty
<p class="lead">You have no public repositories we can access.</p>
@endforelse
<hr>
<h2>Delete Account</h2>
<p class="lead">You may delete your account here.</p>
<p>Note that account deletion will remove all your data from our servers, so if you create a new account in the future, all your current analyses will be missing.</p>
<p class="lead">TODO</p>
@stop
