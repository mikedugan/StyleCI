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

use StyleCI\StyleCI\Commands\DisableRepoCommand;
use StyleCI\StyleCI\Events\RepoWasDisabledEvent;

/**
 * This is the disable repo command handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DisableRepoCommandHandler
{
    /**
     * Handle the disable repo command.
     *
     * @param \StyleCI\StyleCI\Commands\DisableRepoCommand $command
     *
     * @return void
     */
    public function handle(DisableRepoCommand $command)
    {
        $repo = $command->getRepo();

        foreach ($repo->commits as $commit) {
            $commit->delete();
        }

        foreach ($repo->forks as $fork) {
            $fork->delete();
        }

        event(new RepoWasDisabledEvent($repo));

        $repo->delete();
    }
}
