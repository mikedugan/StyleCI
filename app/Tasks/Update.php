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

namespace StyleCI\StyleCI\Tasks;

use StyleCI\StyleCI\Models\Commit;
use Illuminate\Contracts\Queue\Job;

/**
 * This is the update task class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/StyleCI/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class Update extends AbstractTask
{
    /**
     * Kick of the update.
     *
     * @param \Illuminate\Contracts\Queue\Job       $job
     * @param \StyleCI\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    public function fire(Job $job, Commit $commit)
    {
        $this->analyser->runUpdate($commit);

        $job->delete();
    }
}
