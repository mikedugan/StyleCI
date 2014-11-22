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

use GrahamCampbell\StyleCI\Models\Analysis;
use Illuminate\Contracts\Queue\Job;

/**
 * This is the analyse task class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class Analyse
{
    /**
     * Kick of the analysis.
     *
     * @param \Illuminate\Contracts\Queue\Job $job
     * @param int                             $id
     *
     * @return void
     */
    public function fire(Job $job, $id)
    {
        $analysis = Analysis::find($id);

        $this->analyser->analyse($analysis);
    }
}
