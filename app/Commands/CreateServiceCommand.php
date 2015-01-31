<?php

/*
* This file is part of StyleCI.
*
* (c) Graham Campbell <graham@mineuk.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace StyleCI\StyleCI\Commands;

use Laravel\Socialite\Contracts\User as SocialiteUser;
use StyleCI\StyleCI\Models\User;

/**
 * This is the create service command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CreateServiceCommand
{
    /**
     * The socialite user.
     *
     * @var \Laravel\Socialite\Contracts\User
     */
    protected $socialiteUser;

    /**
     * The styleci user.
     *
     * @var \StyleCI\StyleCI\Models\User
     */
    protected $user;

    /**
     * Create a new create service command instance.
     *
     * @param \Laravel\Socialite\Contracts\User $socialiteUser
     * @param \StyleCI\StyleCI\Models\User      $user
     *
     * @return void
     */
    public function __construct(SocialiteUser $socialiteUser, User $user)
    {
        $this->socialiteUser = $socialiteUser;
        $this->user = $user;
    }

    /**
     * Get the socialite user.
     *
     * @return \Laravel\Socialite\Contracts\User
     */
    public function getSocialiteUser()
    {
        return $this->socialiteUser;
    }

    /**
     * Get the styleci user.
     *
     * @return \StyleCI\StyleCI\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
