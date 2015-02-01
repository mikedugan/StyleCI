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

namespace StyleCI\StyleCI\Handlers\Commands;

use StyleCI\StyleCI\Commands\DeleteRepoCommand;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the delete repo command handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DeleteRepoCommandHandler
{
    /**
     * Handle the delete repo command.
     *
     * @param \StyleCI\StyleCI\Commands\DeleteRepoCommand $command
     *
     * @return void
     */
    public function handle(DeleteRepoCommand $command)
    {
        $repo = new Repo();

        foreach ($repo->commits as $commit) {
            $commit->delete();
        }

        foreach ($repo->forks as $fork) {
            $fork->delete();
        }

        $repo->delete();
    }
}
