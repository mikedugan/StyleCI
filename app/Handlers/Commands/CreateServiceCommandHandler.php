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
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CreateServiceCommandHandler
{
    /**
     * Handle the create service command.
     *
     * @param \StyleCI\StyleCI\Commands\CreateServiceCommand $command
     *
     * @return \StyleCI\StyleCI\Models\Service
     */
    public function handle(CreateServiceCommand $command)
    {
        $service = new Service();

        $service->uid = $command->getSocialiteUser()->id;
        $service->user_id = $command->getUser()->id;
        $service->token = $command->getSocialiteUser()->token;

        $service->save();

        return $service;
    }
}
