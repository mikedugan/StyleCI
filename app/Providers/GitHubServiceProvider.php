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
use StyleCI\StyleCI\GitHub\Branches;
use StyleCI\StyleCI\GitHub\ClientFactory;
use StyleCI\StyleCI\GitHub\Collaborators;
use StyleCI\StyleCI\GitHub\Commits;
use StyleCI\StyleCI\GitHub\Hooks;
use StyleCI\StyleCI\GitHub\Repos;
use StyleCI\StyleCI\GitHub\Status;

/**
 * This is the github service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class GitHubServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBranches();
        $this->registerClientFactory();
        $this->registerCollaborators();
        $this->registerCommits();
        $this->registerHooks();
        $this->registerRepos();
        $this->registerStatus();
    }

    /**
     * Register the branches class.
     *
     * @return void
     */
    protected function registerBranches()
    {
        $this->app->singleton('styleci.branches', function ($app) {
            $factory = $app['styleci.clientfactory'];

            return new Branches($factory);
        });

        $this->app->alias('styleci.branches', Branches::class);
    }

    /**
     * Register the client factory class.
     *
     * @return void
     */
    protected function registerClientFactory()
    {
        $this->app->singleton('styleci.clientfactory', function ($app) {
            $factory = $app['github.factory'];

            return new ClientFactory($factory);
        });

        $this->app->alias('styleci.clientfactory', ClientFactory::class);
    }

    /**
     * Register the collaborators class.
     *
     * @return void
     */
    protected function registerCollaborators()
    {
        $this->app->singleton('styleci.collaborators', function ($app) {
            $factory = $app['styleci.clientfactory'];
            $cache = $app['cache.store'];

            return new Collaborators($factory, $cache);
        });

        $this->app->alias('styleci.collaborators', Collaborators::class);
    }

    /**
     * Register the commits class.
     *
     * @return void
     */
    protected function registerCommits()
    {
        $this->app->singleton('styleci.commits', function ($app) {
            $factory = $app['styleci.clientfactory'];

            return new Commits($factory);
        });

        $this->app->alias('styleci.commits', Commits::class);
    }

    /**
     * Register the hooks class.
     *
     * @return void
     */
    protected function registerHooks()
    {
        $this->app->singleton('styleci.hooks', function ($app) {
            $factory = $app['styleci.clientfactory'];

            return new Hooks($factory);
        });

        $this->app->alias('styleci.hooks', Hooks::class);
    }

    /**
     * Register the repos class.
     *
     * @return void
     */
    protected function registerRepos()
    {
        $this->app->singleton('styleci.repos', function ($app) {
            $factory = $app['styleci.clientfactory'];
            $cache = $app['cache.store'];

            return new Repos($factory, $cache);
        });

        $this->app->alias('styleci.repos', Repos::class);
    }

    /**
     * Register the status class.
     *
     * @return void
     */
    protected function registerStatus()
    {
        $this->app->singleton('styleci.status', function ($app) {
            $factory = $app['styleci.clientfactory'];
            $url = asset('commits');

            return new Status($factory, $url);
        });

        $this->app->alias('styleci.status', Status::class);
    }
}
