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
 * This is the enable repo command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class EnableRepoCommand
{
    /**
     * The repository name.
     *
     * @var string
     */
    protected $name;

    /**
     * The associated user.
     *
     * @var \StyleCI\StyleCI\Models\User
     */
    protected $user;

    /**
     * Create a new enable repo command instance.
     *
     * @param string                       $name
     * @param \StyleCI\StyleCI\Models\User $user
     *
     * @return void
     */
    public function __construct($name, User $user)
    {
        $this->name = $name;
        $this->user = $user;
    }

    /**
     * Get the repository name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the associated user.
     *
     * @return \StyleCI\StyleCI\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
