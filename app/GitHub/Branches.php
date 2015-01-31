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

namespace StyleCI\StyleCI\GitHub;

use Github\Api\Repo as GitHub;

/**
 * This is the github branches class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
