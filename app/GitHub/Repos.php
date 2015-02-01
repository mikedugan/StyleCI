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

use Github\ResultPager;
use StyleCI\StyleCI\Models\Repo;
use StyleCI\StyleCI\Models\User;

/**
 * This is the github repos class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Repos
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
     * Get the user's public repos.
     *
     * @param \StyleCI\StyleCI\Models\User $user
     *
     * @return array
     */
    public function get(User $user)
    {
        $client = $this->factory->make($user);
        $paginator = new ResultPager($client);

        $repos = $paginator->fetchAll($client->me(), 'repositories');

        $list = [];

        foreach ($repos as $repo) {
            if ($repo['private']) {
                continue;
            }

            $id = $repo['id'];

            $list[$id] = ['name' => $repo['full_name'], 'enabled' => (Repo::find($id) !== null)];
        }

        return $list;
    }
}
