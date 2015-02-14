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

namespace StyleCI\StyleCI\Repositories;

use StyleCI\StyleCI\GitHub\Repos;
use StyleCI\StyleCI\Models\Repo;
use StyleCI\StyleCI\Models\User;

/**
 * This is the repo repository class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepoRepository
{
    /**
     * The github repos instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Repos
     */
    protected $repos;

    /**
     * Create a new repo repository instance.
     *
     * @param \StyleCI\StyleCI\GitHub\Repos $repos
     *
     * @return void
     */
    public function __construct(Repos $repos)
    {
        $this->repos = $repos;
    }

    /**
     * Find all repos.
     *
     * @param string $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($id)
    {
        return Repo::orderBy('name', 'asc')->get();
    }

    /**
     * Find all repos a user can view.
     *
     * @param \StyleCI\StyleCI\Models\User $user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allByUser(User $user)
    {
        return Repo::whereIn('id', array_keys($this->repos->get($user)))->orderBy('name', 'asc')->get();
    }

    /**
     * Find a repo by its id.
     *
     * @param string $id
     *
     * @return \StyleCI\StyleCI\Models\Repo|null
     */
    public function find($id)
    {
        return Repo::find($id);
    }

    /**
     * Find a repo by its name.
     *
     * @param string $name
     *
     * @return \StyleCI\StyleCI\Models\Repo|null
     */
    public function findByName($name)
    {
        return Repo::where('name', $name)->first();
    }
}
