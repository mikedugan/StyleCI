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

use StyleCI\StyleCI\Commands\CreateServiceCommand;
use StyleCI\StyleCI\Models\Service;

/**
 * This is the create service command handler class.
 *
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CreateServiceCommandHandler
{
    /**
     * Handle the create service command.
     *
     * @param \StyleCI\StyleCI\Commands\CreateServiceCommand;
     *
     * @return \StyleCI\StyleCI\Models\Service
     */
    public function handle(CreateServiceCommand $command)
    {
        $service = new Service();
        $service->uid = $command->socialiteUser->id;
        $service->user_id = $command->user->id;
        $service->provider = $command->provider;

        if ($command->provider === 'github') {
            $service->oauth2_access_token = $command->socialiteUser->token;
        }

        $service->save();

        return $service;
    }
}
