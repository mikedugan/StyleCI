<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 * (c) Joseph Cohen <joseph.cohen@dinkbit.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Contracts\Factory as Socialite;
use StyleCI\StyleCI\Commands\LoginCommand;

/**
 * This is the auth controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class AuthController extends AbstractController
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['handleLogout']]);
        $this->middleware('csrf', ['except' => ['handleCallback']]);
    }

    /**
     * Connect to the GitHub provider using OAuth.
     *
     * @param \Laravel\Socialite\Contracts\Factory $socialite
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogin(Socialite $socialite)
    {
        $response = $socialite->driver('github');

        $response->scopes([
            'repo',
            'user:email',
            'admin:repo_hook',
        ]);

        return $response->redirect();
    }

    /**
     * Get the user access token to save notifications.
     *
     * @param \Laravel\Socialite\Contracts\Factory $socialite
     *
     * @return \Illuminate\Http\Response
     */
    public function handleCallback(Socialite $socialite)
    {
        $socialiteUser = $socialite->driver('github')->user();

        $this->dispatch(new LoginCommand($socialiteUser->id, $socialiteUser->name, $socialiteUser->nickname, $socialiteUser->email, $socialiteUser->token));

        return Redirect::route('repos_path');
    }

    /**
     * Logout a user account.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     *
     * @return \Illuminate\Http\Response
     */
    public function handleLogout(Guard $auth)
    {
        $auth->logout();

        return Redirect::route('home');
    }
}
