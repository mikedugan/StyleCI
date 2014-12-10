<?php

/**
 * This file is part of StyleCI by Graham Campbell.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 */

namespace GrahamCampbell\StyleCI\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the commit model class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class Commit extends Model
{
    /**
     * A list of methods protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = ['_token', '_method'];

    /**
     * Get the repo relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function repo()
    {
        return $this->belongsTo(Repo::class);
    }

    /**
     * Get the fork relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fork()
    {
        return $this->belongsTo(Fork::class);
    }

    /**
     * Get the files relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the commit status code.
     *
     * @return int
     */
    public function status()
    {
        return (int) $this->status;
    }

    /**
     * Get the commit status summary.
     *
     * @return string
     */
    public function summary()
    {
        switch ($this->status()) {
            case 1:
                return 'PASSED';
            case 2:
                return 'FAILED';
            default:
                return 'PENDING';
        }
    }

    /**
     * Get the commit status description.
     *
     * @return string
     */
    public function description()
    {
        switch ($this->status()) {
            case 1:
                return 'The StyleCI checks passed';
            case 2:
                return 'The StyleCI checks failed';
            default:
                return 'The StyleCI checks are pending';
        }
    }

    /**
     * Get the commit's repo name.
     *
     * @return string
     */
    public function name()
    {
        if (empty($this->fork_id)) {
            return $this->repo->name;
        } else {
            return $this->fork->name;
        }
    }
}
