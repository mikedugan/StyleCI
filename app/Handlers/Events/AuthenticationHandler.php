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

namespace StyleCI\StyleCI\Handlers\Events;

use Illuminate\Contracts\Auth\Guard;
use StyleCI\StyleCI\Events\UserHasLoggedInEvent;

/**
 * This is the authentication handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AuthenticationHandler
{
    /**
     * The authentication guard instance.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new authentication handler instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle the user has logged in event.
     *
     * @param \StyleCI\StyleCI\Events\UserHasLoggedInEvent $event
     *
     * @return void
     */
    public function handle(UserHasLoggedInEvent $event)
    {
        $user = $event->getUser();

        $this->auth->login($user, true);
    }
}
