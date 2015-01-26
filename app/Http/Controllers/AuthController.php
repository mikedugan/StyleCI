<?php

/*
* This file is part of StyleCI.
*
* (c) Graham Campbell <graham@mineuk.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace StyleCI\StyleCI\Http\Controllers;

use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use InvalidArgumentException;
use Laravel\Socialite\Contracts\Factory as Socialite;
use StyleCI\StyleCI\Commands\CreateServiceCommand;
use StyleCI\StyleCI\Commands\SignupCommand;
use StyleCI\StyleCI\Models\Service;

/**
 * This is the auth controller class.
 *
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class AuthController extends AbstractController
{
    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;

        $this->middleware('guest', ['except' => 'handleLogout']);
        $this->middleware('csrf', ['only' => ['handleLogin', 'handleSignup']]);
    }

    /**
     * Shows login form.
     *
     * @return \Illuminate\View\View
     */
    public function handleShowLogin()
    {
        return View::make('auth.login');
    }

    /**
     * Login a user to his account.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handleLogin(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            return Redirect::route('home');
        }

        return Redirect::route('auth_login_path')
        ->withInput($request->only('email'))
        ->withError([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Display form to create an account for a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleShowSignup()
    {
        return View::make('auth.signup', [
            'name'  => Binput::old('name'),
            'email' => Binput::old('email'),
        ]);
    }

    /**
     * Create an account for a user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handleSignup(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = $this->dispatch(
            new SignupCommand(
                Binput::get('name'),
                Binput::get('email'),
                Binput::get('password')
            )
        );

        $this->auth->login($user);

        return Redirect::route('home');
    }

    /**
     * Connect to a provider using OAuth.
     *
     * @param \Laravel\Socialite\Contracts\Factory $socialite
     * @param string                               $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleRedirect(Socialite $socialite, $provider)
    {
        try {
            $response = $socialite->driver($provider);
        } catch (InvalidArgumentException $e) {
            return abort(404);
        }

        if ($provider == 'github') {
            $response->scopes([
                'user:email',
                'repo:status',
                'repo_deployment',
                'read:org',
                'write:repo_hook',
            ]);
        }

        return $response->redirect();
    }

    /**
     * Get the user access token to save notifications.
     *
     * @param \Laravel\Socialite\Contracts\Factory $socialite
     * @param string                               $provider
     *
     * @return \Illuminate\Http\Response
     */
    public function handleCallback(Socialite $socialite, $provider)
    {
        try {
            $socialiteUser = $socialite->driver($provider)->user();
        } catch (InvalidArgumentException $e) {
            return Redirect::route('auth_login_path');
        }

        $service = Service::where('uid', '=', $socialiteUser->id)
            ->where('provider', '=', $provider)
            ->first();

        if (! $service) {
            $user = $this->dispatch(
                new SignupCommand($socialiteUser->name, $socialiteUser->email, null)
            );

            $service = $this->dispatch(
                new CreateServiceCommand($socialiteUser, $user, $provider)
            );
        }

        $this->auth->login($service->user, true);

        return Redirect::route('home');
    }

    /**
     * Logout a user from his account.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleLogout()
    {
        $this->auth->logout();

        return Redirect::route('home');
    }
}
