<?php

/*
* This file is part of StyleCI.
*
* (c) Graham Campbell <graham@mineuk.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace StyleCI\StyleCI\Handlers\Commands;

use StyleCI\StyleCI\Commands\SignupCommand;
use StyleCI\StyleCI\Models\User;

/**
 * This is the sign up command handler class.
 *
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class SignupCommandHandler
{
    /**
     * Handle the signup command.
     *
     * @param \StyleCI\StyleCI\Commands\SignupCommand;
     *
     * @return \StyleCI\StyleCI\Models\User
     */
    public function handle(SignupCommand $command)
    {
        $user = new User();
        $user->email = $command->email;
        $user->password = $command->password;
        $user->name = $command->name;
        $user->api_key = User::generateApiKey();
        $user->save();

        return $user;
    }
}
