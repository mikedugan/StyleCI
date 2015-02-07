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

namespace StyleCI\StyleCI\GitHub;

use Github\ResultPager;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use StyleCI\StyleCI\Models\Commit;
use StyleCI\StyleCI\Models\Fork;
use StyleCI\StyleCI\Models\Repo;
use StyleCI\StyleCI\Models\User;

/**
 * This is the github collaborators class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Collaborators
{
    /**
     * The github client factory instance.
     *
     * @var \StyleCI\StyleCI\GitHub\ClientFactory
     */
    protected $factory;

    /**
     * The illuminate cache repository instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a github collaborators instance.
     *
     * @param \StyleCI\StyleCI\GitHub\ClientFactory  $factory
     * @param \Illuminate\Contracts\Cache\Repository $cache
     *
     * @return void
     */
    public function __construct(ClientFactory $factory, Repository $cache)
    {
        $this->factory = $factory;
        $this->cache = $cache;
    }

    /**
     * Get the collaborators for a repo.
     *
     * This method accepts a commit, fork, or repo model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return int[]
     */
    public function get(Model $model)
    {
        // cache the collaborator info from github for 12 hours
        return $this->cache->remember($this->getId($model).'collaborators', 720, function () use ($model) {
            $user = ($model instanceof Commit || $model instanceof Fork) ? $model->repo->user : $model->user;
            $name = ($model instanceof Fork || $model instanceof Repo) ? $model->name : $model->name();

            return $this->fetchFromGitHub($user, $name);
        });
    }

    /**
     * Fetch a repo's collaborators from github.
     *
     * @param \StyleCI\StyleCI\Models\User $user
     * @param string                       $name
     *
     * @return array
     */
    protected function fetchFromGitHub(User $user, $name)
    {
        $client = $this->factory->make($user);
        $paginator = new ResultPager($client);

        $list = [];

        foreach ($paginator->fetchAll($client->repo()->collaborators(), 'all', explode('/', $name)) as $user) {
            $list[] = $user['id'];
        }

        return $list;
    }

    /**
     * Flush our cache of the repo's collaborators.
     *
     * This method accepts a commit, fork, or repo model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function flush(Model $model)
    {
        $this->cache->forget($this->getId($model).'collaborators');
    }

    /**
     * Get the github repo id for the given model.
     *
     * This method accepts a commit, fork, or repo model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return int
     */
    protected function getId(Model $model)
    {
        return ($model instanceof Repo || $model instanceof Fork) ? $model->id : $model->repo->id;
    }
}
