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

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\ServiceProvider;
use StyleCI\StyleCI\Handlers\Middleware\UseDatabaseTransaction;

/**
 * This is the bus service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class BusServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @param \Illuminate\Bus\Dispatcher $dispatcher
     *
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {
        $dispatcher->pipeThrough([UseDatabaseTransaction::class]);

        $dispatcher->mapUsing(function ($command) {
            return Dispatcher::simpleMapping($command, 'StyleCI\StyleCI\Commands', 'StyleCI\StyleCI\Handlers\Commands');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
