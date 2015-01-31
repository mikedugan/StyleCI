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
        // if analysis is pending, then we don't have a time yet
        if ($this->status() === 0) {
            return '-';
        }

        $time = (float) $this->time;

        // display the time to 1 dp if less than 10 secs
        if ($time < 10) {
            return number_format(round($time, 1), 1).' sec';
        }

        // display the time to nearest second if less than 2 min
        if ($time < 120) {
            return number_format(round($time, 0)).' sec';
        }

        // display the time to nearest minute otherwise
        return number_format(round($time / 60, 0)).' min';
    }
}
