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
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CreateServiceCommand
{
    /**
     * Socialite user instance.
     *
     * @var \Laravel\Socialite\Contracts\User
     */
    public $socialiteUser;

    /**
     * User instance.
     *
     * @var \StyleCI\StyleCI\Models\User
     */
    public $user;

    /**
     * Socialite user instance.
     *
     * @var string
     */
    public $provider;

    /**
     * Create a new command instance.
     *
     * @param \Laravel\Socialite\Contracts\User $socialiteUser
     * @param StyleCI\StyleCI\Models\User       $user
     * @param string                            $provider
     *
     * @return void
     */
    public function __construct(SocialiteUser $socialiteUser, User $user, $provider)
    {
        $this->socialiteUser = $socialiteUser;
        $this->user = $user;
        $this->provider = $provider;
    }
}
