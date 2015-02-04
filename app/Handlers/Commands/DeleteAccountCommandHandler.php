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

use Illuminate\Foundation\Bus\DispatchesCommands;
use StyleCI\StyleCI\Commands\DeleteAccountCommand;
use StyleCI\StyleCI\Commands\DisableRepoCommand;
use StyleCI\StyleCI\Events\UserHasRageQuitEvent;

/**
 * This is the delete account command handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DeleteAccountCommandHandler
{
    use DispatchesCommands;

    /**
     * Handle the delete account command.
     *
     * @param \StyleCI\StyleCI\Commands\DeleteAccountCommand $command
     *
     * @return void
     */
    public function handle(DeleteAccountCommand $command)
    {
        $user = $command->getUser();

        foreach ($user->repos as $repo) {
            $this->dispatch(new DisableRepoCommand($repo));
        }

        event(new UserHasRageQuitEvent($user));

        $user->delete();
    }
}
