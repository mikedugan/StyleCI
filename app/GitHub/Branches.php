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

use StyleCI\StyleCI\Model\Repo;

/**
 * This is the github branches class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Branches
{
    /**
     * The github client factory instance.
     *
     * @var \StyleCI\StyleCI\GitHub\ClientFactory
     */
    protected $factory;

    /**
     * Create a github branches instance.
     *
     * @param \StyleCI\StyleCI\GitHub\ClientFactory $factory
     *
     * @return void
     */
    public function __construct(ClientFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Get the branches from a github repo.
     *
     * @param \StyleCI\StyleCI\Model\Repo $repo
     *
     * @return array
     */
    public function get(Repo $repo)
    {
        $args = explode('/', $repo->name);

        $raw = $this->factory->make($repo)->repos()->branches($args[0], $args[1]);

        $branches = [];

        foreach ($raw as $branch) {
            if ((strpos($branch['name'], 'gh-pages') === false)) {
                $branches[] = ['name' => $branch['name'], 'commit' => $branch['commit']['sha']];
            }
        }

        return $branches;
    }
}
