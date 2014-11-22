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

namespace GrahamCampbell\StyleCI\Factories;

use Exception;
use GrahamCampbell\StyleCI\Models\Commit;
use GrahamCampbell\StyleCI\Models\Repo;

/**
 * This is the model factory class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class ModelFactory
{
    /**
     * Make a repo model.
     *
     * @param string $name
     *
     * @return \GrahamCampbell\StyleCI\Models\Repo
     */
    public function repo($name)
    {
        $id = sha1($name);

        if ($repo = Repo::find($id)) {
            return $repo;
        }

        Repo::create(['id' => $id, 'name' -> $name]);

        return Repo::find($id);
    }

    /**
     * Make a commit model.
     *
     * @param string $id
     *
     * @return \GrahamCampbell\StyleCI\Models\Commit
     */
    public function commit($id, $repo = null)
    {
        if ($commit = Commit::find($id)) {
            return $commit;
        }

        if (!$repo) {
            throw new Exception('The commit has not been attached to a repo yet.');
        }

        Commit::create(['id' => $id, 'repo_id' => $repo]);

        return Commit::find($id);
    }
}
