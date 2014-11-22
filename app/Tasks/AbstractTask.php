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

namespace GrahamCampbell\StyleCI\Tasks;

use GrahamCampbell\StyleCI\Analyser;
use GrahamCampbell\StyleCI\Models\Commit;
use Illuminate\Contracts\Queue\Job;

/**
 * This is the abstract task class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
abstract class AbstractTask
{
    /**
     * The analyser instance.
     *
     * @var \GrahamCampbell\StyleCI\Analyser
     */
    protected $analyser;

    /**
     * Create a task instance.
     *
     * @param \GrahamCampbell\StyleCI\Analyser $analyser
     *
     * @return void
     */
    public function __construct(Analyser $analyser)
    {
        $this->analyser = $analyser;
    }
}
