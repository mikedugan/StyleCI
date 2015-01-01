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
 * This is the file model class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class File extends Model
{
    /**
     * A list of methods protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = ['_token', '_method', 'id'];

    /**
     * Are timestamps enabled?
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the commit relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commit()
    {
        return $this->belongsTo(Commit::class);
    }
}
