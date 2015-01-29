@extends(Config::get('core.layout'))

@section('title')
Sign up
@stop

@section('content')
<header id="top" class="header">
    <div class="text-vertical-center">
        <h1>StyleCI</h1>
        <h3>Sign up</h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p>
                Sign up with GitHub
            </p>
            <a class="btn btn-block btn-lg btn-default" href="{{ route('auth_connect_path', 'github') }}">
                <i class="fa fa-github-square"></i>
                Sign in with GitHub
            </a>
            <hr>
            <form id="form_id" action="{{ route('auth_signup_path') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group @if ($errors->has('name')) has-error @endif">
                    <label for="name">Name</label>
                    <input class="form-control" type="text" name="name" value="{{ $name or '' }}" required>
                    @if ($errors->has('name'))
                    <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ $email or '' }}" required>
                    @if ($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group @if ($errors->has('password')) has-error @endif">
                    <label for="password">Password</label>
                    <input class="form-control" type="password" name="password" value="" required>
                    @if ($errors->has('password'))
                    <span class="help-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="form-group @if ($errors->has('password')) has-error @endif">
                    <label for="password_confirmation">Confirm  Password</label>
                    <input class="form-control" type="password" name="password_confirmation" value="" required>
                </div>
                <button type="submit" class="btn btn-dark btn-lg">Sign up</button>
            </form>
            <br>
            <a href="{{ route('auth_login_path') }}"> Already have an account?</a>
        </div>
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
    padding-top: 60px;
    display: table;
    position: relative;
    width: 100%;
}
</style>
@stop
