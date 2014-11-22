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
     * Get the files relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the combined commit status.
     *
     * @return int
     */
    public function combinedStatus()
    {
        if ($this->status === '0' || $this->travis === '0') {
            return 0;
        }

        if (($this->status === '1' || $this->status === '3') && ($this->travis === '1' || $this->travis === '3')) {
            return 1;
        }

        return 2;
    }

    /**
     * Get the commit summary.
     *
     * @return int
     */
    public function summary()
    {
        if ($this->travis === '0') {
            $tests = 'PENDING';
        } elseif ($this->travis === '1') {
            $tests = 'PASSED';
        } elseif ($this->travis === '2') {
            $tests = 'FAILED';
        } else {
            $tests = 'SKIPPED';
        }

        if ($this->status === '0') {
            $cs = 'PENDING';
        } elseif ($this->status === '1') {
            $cs = 'PASSED';
        } elseif ($this->status === '2') {
            $cs = 'FAILED';
        } else {
            $cs = 'SKIPPED';
        }

        return "TESTS: $tests — CS: $cs";
    }
}
