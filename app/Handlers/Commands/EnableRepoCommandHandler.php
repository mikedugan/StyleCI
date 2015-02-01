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

use StyleCI\StyleCI\Commands\EnableRepoCommand;
use StyleCI\StyleCI\GitHub\Hooks;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the enable repo command handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class EnableRepoCommandHandler
{
    /**
     * The hooks instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Hooks
     */
    protected $hooks;

    /**
     * Create a new enable repo command handler instance.
     *
     * @param \StyleCI\StyleCI\GitHub\Hooks $hooks
     *
     * @return void
     */
    public function __construct(Hooks $hooks)
    {
        $this->hooks = $hooks;
    }

    /**
     * Handle the enable repo command.
     *
     * @param \StyleCI\StyleCI\Commands\EnableRepoCommand $command
     *
     * @return void
     */
    public function handle(EnableRepoCommand $command)
    {
        $repo = new Repo();

        $repo->id = $command->getId();
        $repo->name = $command->getName();
        $repo->user_id = $command->getUser()->id;

        $repo->save();

        // disable all styleci hooks before adding the new one in case any old
        // conflicting ones are left over, then add the new webhook
        $this->hooks->disable($repo);
        $this->hooks->enable($repo);
    }
}
