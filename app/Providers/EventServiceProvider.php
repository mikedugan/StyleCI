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

namespace StyleCI\StyleCI\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * This is the event service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'StyleCI\StyleCI\Events\AnalysisHasCompletedEvent' => [
            'StyleCI\StyleCI\Handlers\Events\CommitStatusHandler',
            'StyleCI\StyleCI\Handlers\Events\AnalysisNotificationsHandler',
            'StyleCI\StyleCI\Handlers\Events\RealTimeStatusHandler',
        ],
        'StyleCI\StyleCI\Events\AnalysisWasQueuedEvent' => [
            'StyleCI\StyleCI\Handlers\Events\RealTimeStatusHandler',
        ],
        'StyleCI\StyleCI\Events\RepoWasDisabledEvent' => [
            'StyleCI\StyleCI\Handlers\Events\DisableHooksHandler',
        ],
        'StyleCI\StyleCI\Events\RepoWasEnabledEvent' => [
            'StyleCI\StyleCI\Handlers\Events\EnableHooksHandler',
        ],
        'StyleCI\StyleCI\Events\UserHasLoggedInEvent' => [
            'StyleCI\StyleCI\Handlers\Events\AuthenticationHandler',
            'StyleCI\StyleCI\Handlers\Events\RepoCacheFlushHandler',
        ],
        'StyleCI\StyleCI\Events\UserHasRageQuitEvent' => [
            'StyleCI\StyleCI\Handlers\Events\RevokeTokenHandler',
        ],
    ];
}
