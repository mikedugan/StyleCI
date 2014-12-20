<?php

/*
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

namespace StyleCI\StyleCI\Factories;

use Exception;
use StyleCI\StyleCI\Models\Commit;
use StyleCI\StyleCI\Models\Fork;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the model factory class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/StyleCI/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class ModelFactory
{
    /**
     * Make a repo model.
     *
     * @param string $name
     *
     * @return \StyleCI\StyleCI\Models\Repo
     */
    public function repo($name)
    {
        $id = sha1($name);

        if ($repo = Repo::find($id)) {
            return $repo;
        }

        Repo::create(['id' => $id, 'name' => $name]);

        return Repo::find($id);
    }

    /**
     * Make a fork model.
     *
     * @param string      $name
     * @param string|null $repo
     *
     * @return \StyleCI\StyleCI\Models\Fork
     */
    public function fork($name, $repo = null)
    {
        $id = sha1($name);

        if ($fork = Fork::find($id)) {
            return $fork;
        }

        if (!$repo) {
            throw new Exception('The fork has not been attached to a repo yet.');
        }

        Fork::create(['id' => $id, 'repo_id' => $repo, 'name' => $name]);

        return Fork::find($id);
    }

    /**
     * Make a commit model.
     *
     * @param string      $id
     * @param string|null $repo
     * @param string|null $fork
     *
     * @return \StyleCI\StyleCI\Models\Commit
     */
    public function commit($id, $repo = null, $fork = null)
    {
        if ($commit = Commit::find($id)) {
            return $commit;
        }

        if (!$repo) {
            throw new Exception('The commit has not been attached to a repo yet.');
        }

        if ($fork) {
            Commit::create(['id' => $id, 'repo_id' => $repo, 'fork_id' => $fork]);
        } else {
            Commit::create(['id' => $id, 'repo_id' => $repo]);
        }

        return Commit::find($id);
    }
}
