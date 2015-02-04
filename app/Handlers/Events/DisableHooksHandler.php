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

namespace StyleCI\StyleCI\Handlers\Events;

use StyleCI\StyleCI\Events\RepoWasDisabledEvent;
use StyleCI\StyleCI\GitHub\Hooks;

/**
 * This is the disable hooks handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DisableHooksHandler
{
    /**
     * The hooks instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Hooks
     */
    protected $hooks;

    /**
     * Create a new disable hooks handler instance.
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
     * Handle the repo was disabled event.
     *
     * @param \StyleCI\StyleCI\Events\RepoWasDisabledEvent $event
     *
     * @return void
     */
    public function handle(RepoWasDisabledEvent $event)
    {
        $this->hooks->disable($repo);
    }
}
