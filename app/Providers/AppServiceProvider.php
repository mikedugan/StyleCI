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
use StyleCI\StyleCI\GitHub\Hooks;
use StyleCI\StyleCI\GitHub\Repos;
use StyleCI\StyleCI\GitHub\Status;
use StyleCI\StyleCI\Http\Middleware\Authenticate;

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
        $this->registerGitHubClientFactory();
        $this->registerGitHubBranches();
        $this->registerGitHubHooks();
        $this->registerGitHubRepos();
        $this->registerGitHubStatus();
        $this->registerAuthenticate();
    }

    /**
     * Register the github client factory class.
     *
     * @return void
     */
    protected function registerGitHubClientFactory()
    {
        $this->app->singleton('styleci.clientfactory', function ($app) {
            $factory = $app['github.factory'];

            return new ClientFactory($factory);
        });

        $this->app->alias('styleci.clientfactory', 'StyleCI\StyleCI\GitHub\ClientFactory');
    }

    /**
     * Register the github branches class.
     *
     * @return void
     */
    protected function registerGitHubBranches()
    {
        $this->app->singleton('styleci.branches', function ($app) {
            $factory = $app['styleci.clientfactory'];

            return new Branches($factory);
        });

        $this->app->alias('styleci.branches', 'StyleCI\StyleCI\GitHub\Branches');
    }

    /**
     * Register the github hooks class.
     *
     * @return void
     */
    protected function registerGitHubHooks()
    {
        $this->app->singleton('styleci.hooks', function ($app) {
            $factory = $app['styleci.clientfactory'];

            return new Hooks($factory);
        });

        $this->app->alias('styleci.hooks', 'StyleCI\StyleCI\GitHub\Hooks');
    }

    /**
     * Register the github repos class.
     *
     * @return void
     */
    protected function registerGitHubRepos()
    {
        $this->app->singleton('styleci.repos', function ($app) {
            $factory = $app['styleci.clientfactory'];

            return new Repos($factory);
        });

        $this->app->alias('styleci.repos', 'StyleCI\StyleCI\GitHub\Repos');
    }

    /**
     * Register the github status class.
     *
     * @return void
     */
    protected function registerGitHubStatus()
    {
        $this->app->singleton('styleci.status', function ($app) {
            $factory = $app['styleci.clientfactory'];
            $url = asset('commits');

            return new Status($factory, $url);
        });

        $this->app->alias('styleci.status', 'StyleCI\StyleCI\GitHub\Status');
    }

    /**
     * Register the auth middleware.
     *
     * @return void
     */
    protected function registerAuthenticate()
    {
        $this->app->singleton('StyleCI\StyleCI\Http\Middleware\Authenticate', function ($app) {
            $auth = $app['auth.driver'];
            $allowed = $app->config->get('styleci.allowed', []);

            return new Authenticate($auth, $allowed);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'styleci.hooks',
            'styleci.repos',
            'styleci.status',
            'styleci.branches',
            'styleci.clientfactory',
        ];
    }
}
