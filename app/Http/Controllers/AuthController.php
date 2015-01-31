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

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Contracts\Factory as Socialite;
use StyleCI\StyleCI\Commands\CreateServiceCommand;
use StyleCI\StyleCI\Commands\CreateAccountCommand;
use StyleCI\StyleCI\Models\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This is the auth controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;

        $this->middleware('guest', ['except' => 'handleLogout']);
    }

    /**
     * Connect to the GitHub provider using OAuth.
     *
     * @param \Laravel\Socialite\Contracts\Factory $socialite
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogin(Socialite $socialite)
    {
        $response = $socialite->driver('github');

        $response->scopes([
            'user:email',
            'repo:status',
            'read:org',
            'write:repo_hook',
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

        $service = Service::where('uid', '=', $socialiteUser->id)->first();

        // if the service doesn't exist yet, we need to create it
        if (!$service) {
            $user = $this->dispatch(new CreateAccountCommand($socialiteUser->name, $socialiteUser->email));
            $service = $this->dispatch(new CreateServiceCommand($socialiteUser, $user));
        }

        $this->auth->login($service->user, true);

        return Redirect::route('home');
    }

    /**
     * Logout a user account.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleLogout()
    {
        $this->auth->logout();

        return Redirect::route('home');
    }
}
