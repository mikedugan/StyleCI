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

namespace GrahamCampbell\StyleCI\GitHub;

use Github\Api\Repo as GitHub;

/**
 * This is the github branches class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class Branches
{
    /**
     * The github repo instance.
     *
     * @var \Github\Api\Repo
     */
    protected $github;

    /**
     * Create a github branches instance.
     *
     * @param \Github\Api\Repo $github
     *
     * @return void
     */
    public function __construct(GitHub $github)
    {
        $this->github = $github;
    }

    /**
     * Get the branches from a github repo.
     *
     * @param string $repo
     *
     * @return array
     */
    public function get($repo)
    {
        $args = explode('/', $repo);

        $raw = $this->github->branches($args[0], $args[1]);

        $branches = [];

        foreach ($raw as $branch) {
            if ((strpos($branch['name'], 'gh-pages') === false)) {
                $branches[] = ['name' => $branch['name'], 'commit' => $branch['commit']['sha']];
            }
        }

        return $branches;
    }
}
