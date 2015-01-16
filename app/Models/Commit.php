<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the commit model class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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

    /**
     * Get the commit's repo shorthand id.
     *
     * @return string
     */
    public function shorthandId()
    {
        return substr($this->id, 0, 6);
    }

    /**
     * Get the commit's style check executed time.
     *
     * @return string
     */
    public function excecutedTime()
    {
        $time = '';
        $minutes = floor($this->time / 60);
        $seconds = $this->time % 60;

        if ($minutes > 0) {
            $time .= "{$minutes} min ";
        }

        if ($seconds > 0) {
            $time .= "{$seconds} sec";
        }

        return $time;
    }
}
