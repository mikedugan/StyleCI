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
     * Enable the styleci webhook for the given repo.
     *
     * @param \StyleCI\StyleCI\Models\Repo $repo
     *
     * @return void
     */
    public function enable(Repo $repo)
    {
        $args = explode('/', $repo->name);
        $callback = route('webhook_callback');
        $hooks = $this->factory->make($repo)->repo()->hooks();

        $events = ['pull_request','push'];

        $config = [
            'url'          => $callback,
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
        $args = explode('/', $repo->name);
        $callback = route('webhook_callback');
        $hooks = $this->factory->make($repo)->repo()->hooks();

        foreach ($hooks->all($args[0], $args[1]) as $hook) {
            if ($hook['name'] !== 'web' || $hook['config']['url'] !== $callback) {
                continue;
            }

            $hooks->remove($args[0], $args[1], $hook['id']);
        }
    }
}
