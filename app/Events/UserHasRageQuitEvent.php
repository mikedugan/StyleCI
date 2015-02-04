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

namespace StyleCI\StyleCI\Events;

use StyleCI\StyleCI\Models\User;

/**
 * This is the user has rage quit event class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class UserHasRageQuitEvent
{
    /**
     * The user that has rage quit.
     *
     * @var \StyleCI\StyleCI\Models\User
     */
    protected $user;

    /**
     * Create a new user has rage quit event instance.
     *
     * @param \StyleCI\StyleCI\Models\User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user that has rage quit.
     *
     * @return \StyleCI\StyleCI\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
