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

use McCool\LaravelAutoPresenter\PresenterDecorator;
use StyleCI\StyleCI\Events\AnalysisHasCompletedEvent;
use StyleCI\StyleCI\Models\Commit;
use Vinkla\Pusher\PusherManager;

/**
 * This is the real time status handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class RealTimeStatusHandler
{
    /**
     * The pusher instance.
     *
     * @var \Vinkla\Pusher\PusherManager
     */
    protected $pusher;

    /**
     * The presenter instance.
     *
     * @var \McCool\LaravelAutoPresenter\PresenterDecorator
     */
    protected $presenter;

    /**
     * Create a new analysis notifications handler instance.
     *
     * @param \Vinkla\Pusher\PusherManager                    $pusher
     * @param \McCool\LaravelAutoPresenter\PresenterDecorator $presenter
     *
     * @return void
     */
    public function __construct(PusherManager $pusher, PresenterDecorator $presenter)
    {
        $this->pusher = $pusher;
        $this->presenter = $presenter;
    }

    /**
     * Handle the analysis has completed event.
     *
     * @param \StyleCI\StyleCI\Events\AnalysisHasCompletedEvent|\StyleCI\StyleCI\Events\AnalysisWasQueuedEvent $event
     *
     * @return void
     */
    public function handle($event)
    {
        $commit = $this->presenter->decorate($event->getCommit());

        if ($commit->ref !== 'refs/heads/master') {
            return;
        }

        $this->pusher->trigger('ch-'.$commit->repo_id, 'CommitStatusUpdatedEvent', ['event' => $commit->toArray()]);
    }
}
