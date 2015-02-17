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

use StyleCI\StyleCI\Events\UserHasRageQuitEvent;
use StyleCI\StyleCI\GitHub\Tokens;

/**
 * This is the revoke token handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RevokeTokenHandler
{
    /**
     * The tokens instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Tokens
     */
    protected $tokens;

    /**
     * Create a new revoke token handler instance.
     *
     * @param \StyleCI\StyleCI\GitHub\Tokens $tokens
     *
     * @return void
     */
    public function __construct(Tokens $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Handle the user has rage quit event.
     *
     * @param \StyleCI\StyleCI\Events\UserHasRageQuitEvent $event
     *
     * @return void
     */
    public function handle(UserHasRageQuitEvent $event)
    {
        $user = $event->getUser();

        $this->tokens->revoke($user->access_token);
    }
}
