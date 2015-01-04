<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI;

use Illuminate\Support\ServiceProvider;

/**
 * This is the styleci service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class StyleCIServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('styleci/styleci', 'styleci/styleci', __DIR__);
    }

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
        $this->registerAnalyser();
    }

    /**
     * Register the model factory class.
     *
     * @return void
     */
    protected function registerModelFactory()
    {
        $this->app->singleton('styleci.modelfactory', function () {
            return new Factories\ModelFactory();
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

            return new GitHub\Branches($github);
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

            return new GitHub\Status($github, $url);
        });

        $this->app->alias('styleci.status', 'StyleCI\StyleCI\GitHub\Status');
    }

    /**
     * Register the analyser class.
     *
     * @return void
     */
    protected function registerAnalyser()
    {
        $this->app->singleton('styleci.analyser', function ($app) {
            $fixer = $app['fixer'];
            $status = $app['styleci.status'];
            $queue = $app['queue.connection'];
            $mailer = $app['mailer'];
            $address = $app['config']['mail']['destination'];

            return new Analyser($fixer, $status, $queue, $mailer, $address);
        });

        $this->app->alias('styleci.analyser', 'StyleCI\StyleCI\Analyser');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'styleci.analyser',
            'styleci.branches',
            'styleci.status',
            'styleci.modelfactory',
        ];
    }
}
