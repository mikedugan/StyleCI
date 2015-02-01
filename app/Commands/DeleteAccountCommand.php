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

namespace StyleCI\StyleCI\Commands;

use StyleCI\StyleCI\Models\User;

/**
 * This is the delete account command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DeleteAccountCommand
{
    /**
     * The user to delete.
     *
     * @var string
     */
    protected $user;

    /**
     * Create a new delete account command instance.
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
     * Get the user to delete.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
}
