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
use StyleCI\StyleCI\Repositories\CommitRepository;
use StyleCI\StyleCI\Repositories\ForkRepository;
use StyleCI\StyleCI\Repositories\RepoRepository;
use StyleCI\StyleCI\Repositories\UserRepository;

/**
 * This is the repository service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommitRepository();
        $this->registerForkRepository();
        $this->registerRepoRepository();
        $this->registerUserRepository();
    }

    /**
     * Register the commit repository.
     *
     * @return void
     */
    protected function registerCommitRepository()
    {
        $this->app->singleton('styleci.commitrepository', function ($app) {
            $commits = $app['styleci.commits'];

            return new CommitRepository($commits);
        });

        $this->app->alias('styleci.commitrepository', CommitRepository::class);
    }

    /**
     * Register the fork repository.
     *
     * @return void
     */
    protected function registerForkRepository()
    {
        $this->app->singleton('styleci.forkrepository', function () {
            return new ForkRepository();
        });

        $this->app->alias('styleci.forkrepository', ForkRepository::class);
    }

    /**
     * Register the repo repository.
     *
     * @return void
     */
    protected function registerRepoRepository()
    {
        $this->app->singleton('styleci.reporepository', function ($app) {
            $repos = $app['styleci.repos'];

            return new RepoRepository($repos);
        });

        $this->app->alias('styleci.reporepository', RepoRepository::class);
    }

    /**
     * Register the user repository.
     *
     * @return void
     */
    protected function registerUserRepository()
    {
        $this->app->singleton('styleci.userrepository', function ($app) {
            $collaborators = $app['styleci.collaborators'];

            return new UserRepository($collaborators);
        });

        $this->app->alias('styleci.userrepository', UserRepository::class);
    }
}
