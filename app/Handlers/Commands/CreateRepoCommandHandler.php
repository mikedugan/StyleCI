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

use StyleCI\StyleCI\Commands\CreateRepoCommand;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the create repo command handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CreateRepoCommandHandler
{
    /**
     * Handle the create repo command.
     *
     * @param \StyleCI\StyleCI\Commands\CreateRepoCommand $command
     *
     * @return \StyleCI\StyleCI\Models\Service
     */
    public function handle(CreateRepoCommand $command)
    {
        $repo = new Repo();

        $name = $command->getName();

        $repo->id = sha1($name);
        $repo->name = $name;

        $service->service_id = $command->getService()->id;

        $service->save();
    }
}
