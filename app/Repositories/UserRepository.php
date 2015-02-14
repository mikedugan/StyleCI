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

use Illuminate\Database\Eloquent\Model;
use StyleCI\StyleCI\GitHub\Collaborators;
use StyleCI\StyleCI\Models\User;

/**
 * This is the user repository class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class UserRepository
{
    /**
     * The collaborators instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Collaborators
     */
    protected $collaborators;

    /**
     * Create a new user repository instance.
     *
     * @param \StyleCI\StyleCI\GitHub\Collaborators $collaborators
     *
     * @return void
     */
    public function __construct(Collaborators $collaborators)
    {
        $this->collaborators = $collaborators;
    }

    /**
     * Find all users marked as collaborators to the provided model.
     *
     * @param \Illuminate\Database\Eloquent\Model
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collaborators(Model $model)
    {
        return User::whereIn('id', $this->collaborators->get($model))->get();
    }

    /**
     * Find a user by its id.
     *
     * @param string $id
     *
     * @return \StyleCI\StyleCI\Models\User|null
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * Find a user by its id, or generate a new one.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return \StyleCI\StyleCI\Models\User
     */
    public function findOrGenerate($id, array $attributes = [])
    {
        $user = $this->find($id);

        // if the user exists, we're done here
        if ($user) {
            return $user;
        }

        // otherwise, create a new user, allowing the id to be overwritten
        return (new User())->forceFill(array_merge(['id' => $id], $attributes));
    }
}
