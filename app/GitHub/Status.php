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

use Github\Api\Repository\Statuses as GitHub;

/**
 * This is the github status class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class Status
{
    /**
     * The github statuses instance.
     *
     * @var \Github\Api\Repository\Statuses
     */
    protected $github;

    /**
     * The target url.
     *
     * @var string
     */
    protected $url;

    /**
     * Create a github status instance.
     *
     * @param \Github\Api\Repository\Statuses $github
     * @param string                          $url
     *
     * @return void
     */
    public function __construct(GitHub $github, $url)
    {
        $this->github = $github;
        $this->url = $url;
    }

    /**
     * Mark the status as pending.
     *
     * @param string $repo
     * @param string $commit
     *
     * @return void
     */
    public function pending($repo, $commit)
    {
        $this->set($repo, $commit, 'pending');
    }

    /**
     * Mark the status as successful.
     *
     * @param string $repo
     * @param string $commit
     * @param string $description
     *
     * @return void
     */
    public function successful($repo, $commit, $description)
    {
        $this->set($repo, $commit, 'successful', $description);
    }

    /**
     * Mark the status as errored.
     *
     * @param string $repo
     * @param string $commit
     * @param string $description
     *
     * @return void
     */
    public function error($repo, $commit, $description)
    {
        $this->set($repo, $commit, 'error', $description);
    }

    /**
     * Mark the status as failed.
     *
     * @param string $repo
     * @param string $commit
     * @param string $description
     *
     * @return void
     */
    public function failure($repo, $commit, $description)
    {
        $this->set($repo, $commit, 'failure', $description);
    }

    /**
     * Set the status on the github commit.
     *
     * @param string      $repo
     * @param string      $commit
     * @param string      $state
     * @param string|null $description
     *
     * @return void
     */
    protected function set($repo, $commit, $state, $description = null)
    {
        $repo = explode('/', $repo);

        $data = ['state' => $state, 'target_url' => $this->url.'/'.$commit, 'context' => 'styleci'];

        if ($description) {
            $data['description'] = $description;
        }

        $this->github->create($repo[0], $repo[1], $commit, $data);
    }
}
