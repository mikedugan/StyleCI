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

use StyleCI\StyleCI\Events\UserHasLoggedInEvent;
use StyleCI\StyleCI\GitHub\Repos;

/**
 * This is the repo cache flush handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepoCacheFlushHandler
{
    /**
     * The github repos instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Repos
     */
    protected $repos;

    /**
     * Create a new repo cache flush handler instance.
     *
     * @param \StyleCI\StyleCI\GitHub\Repos $repos
     *
     * @return void
     */
    public function __construct(Repos $repos)
    {
        $this->repos = $repos;
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

        $this->repos->flush($user);
    }
}
