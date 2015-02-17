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
use Stringy\StaticStringy;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the github hooks class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Hooks
{
    /**
     * The github client factory instance.
     *
     * @var \StyleCI\StyleCI\GitHub\ClientFactory
     */
    protected $factory;

    /**
     * Create a new github hooks instance.
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
     * Enable the styleci webhook for the given repo.
     *
     * @param \StyleCI\StyleCI\Models\Repo $repo
     *
     * @return void
     */
    public function enable(Repo $repo)
    {
        $url = route('webhook_callback');
        $args = explode('/', $repo->name);
        $hooks = $this->factory->make($repo)->repo()->hooks();

        $events = ['pull_request','push'];

        $config = [
            'url'          => $url,
            'content_type' => 'json',
            'insecure_ssl' => 0,
            'secret'       => '',
        ];

        $hooks->create($args[0], $args[1], ['name' => 'web', 'events' => $events, 'config' => $config]);
    }

    /**
     * Disable the styleci webhook for the given repo.
     *
     * @param \StyleCI\StyleCI\Models\Repo $repo
     *
     * @return void
     */
    public function disable(Repo $repo)
    {
        $url = route('home'); // we want to remove all hooks containing the base url
        $args = explode('/', $repo->name);
        $client = $this->factory->make($repo);
        $hooks = $client->repo()->hooks();
        $paginator = new ResultPager($client);

        foreach ($paginator->fetchAll($hooks, 'all', $args) as $hook) {
            if ($hook['name'] !== 'web' || StaticStringy::contains($hook['config']['url'], $url, false) !== true) {
                continue;
            }

            $hooks->remove($args[0], $args[1], $hook['id']);
        }
    }
}
