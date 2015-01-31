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

use StyleCI\StyleCI\Commands\CreateAccountCommand;
use StyleCI\StyleCI\Models\User;

/**
 * This is the create account command handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CreateAccountCommandHandler
{
    /**
     * Handle the create account command.
     *
     * @param \StyleCI\StyleCI\Commands\CreateAccountCommand;
     *
     * @return \StyleCI\StyleCI\Models\User
     */
    public function handle(CreateAccountCommand $command)
    {
        $user = new User();

        $user->name = $command->getName();
        $user->email = $command->getEmail();
        $user->api_key = str_random(20);

        $user->save();

        return $user;
    }
}
