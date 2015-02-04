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

use StyleCI\StyleCI\Events\AnalysisHasCompletedEvent;

/**
 * This is the analysis notification handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalysisNotificationsHandler
{
    /**
     * Handle the analysis has completed event.
     *
     * @param \StyleCI\StyleCI\Events\AnalysisHasCompletedEvent $events
     *
     * @return void
     */
    public function handle(AnalysisHasCompletedEvent $event)
    {
        $commit = $event->getCommit();

        // TODO: send notifications off to various places
    }
}
