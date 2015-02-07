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

namespace StyleCI\StyleCI\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use StyleCI\StyleCI\Presenters\CommitPresenter;

/**
 * This is the commit model class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Commit extends Model implements HasPresenter
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * A list of methods protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = ['_token', '_method'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'int',
        'time'   => 'float',
        'memory' => 'float',
    ];

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
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return CommitPresenter::class;
    }

    /**
     * Get the commit status description.
     *
     * @return string
     */
    public function description()
    {
        switch ($this->status) {
            case 1:
                return 'The StyleCI checks passed';
            case 2:
                return 'The StyleCI checks failed';
            default:
                return 'The StyleCI checks are pending';
        }
    }
}
