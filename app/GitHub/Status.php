<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\GitHub;

use Github\Api\Repository\Statuses as GitHub;

/**
 * This is the github status class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
     * @param string $description
     *
     * @return void
     */
    public function pending($repo, $commit, $description)
    {
        $this->set($repo, $commit, 'pending', $description);
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
    public function success($repo, $commit, $description)
    {
        $this->set($repo, $commit, 'success', $description);
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
     * @param string $repo
     * @param string $commit
     * @param string $state
     * @param string $description
     *
     * @return void
     */
    protected function set($repo, $commit, $state, $description)
    {
        $args = explode('/', $repo);

        $data = [
            'state'       => $state,
            'description' => $description,
            'target_url'  => $this->url.'/'.$commit,
            'context'     => 'StyleCI',
        ];

        $this->github->create($args[0], $args[1], $commit, $data);
    }
}
