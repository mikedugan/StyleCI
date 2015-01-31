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

use Illuminate\Support\ServiceProvider;
use StyleCI\StyleCI\Factories\ModelFactory;
use StyleCI\StyleCI\GitHub\Branches;
use StyleCI\StyleCI\GitHub\Status;

/**
 * This is the app service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerModelFactory();
        $this->registerGitHubBranches();
        $this->registerGitHubStatus();
    }

    /**
     * Register the model factory class.
     *
     * @return void
     */
    protected function registerModelFactory()
    {
        $this->app->singleton('styleci.modelfactory', function () {
            return new ModelFactory();
        });

        $this->app->alias('styleci.modelfactory', 'StyleCI\StyleCI\Factories\ModelFactory');
    }

    /**
     * Register the github branches class.
     *
     * @return void
     */
    protected function registerGitHubBranches()
    {
        $this->app->singleton('styleci.branches', function ($app) {
            $github = $app['github']->connection()->repos();

            return new Branches($github);
        });

        $this->app->alias('styleci.branches', 'StyleCI\StyleCI\GitHub\Branches');
    }

    /**
     * Register the github status class.
     *
     * @return void
     */
    protected function registerGitHubStatus()
    {
        $this->app->singleton('styleci.status', function ($app) {
            $github = $app['github']->connection()->repos()->statuses();
            $url = asset('commits');

            return new Status($github, $url);
        });

        $this->app->alias('styleci.status', 'StyleCI\StyleCI\GitHub\Status');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'styleci.branches',
            'styleci.status',
            'styleci.modelfactory',
        ];
    }
}
