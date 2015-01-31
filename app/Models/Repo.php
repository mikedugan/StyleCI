<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Joseph Cohen <joseph.cohen@dinkbit.com>
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the repo model class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Repo extends Model
{
    /**
     * A list of methods protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = ['_token', '_method'];

    /**
     * Get the commits relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commits()
    {
        return $this->hasMany(Commit::class);
    }

    /**
     * Get the forks relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forks()
    {
        return $this->hasMany(Fork::class);
    }
}
