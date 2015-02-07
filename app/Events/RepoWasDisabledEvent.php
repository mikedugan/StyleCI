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

namespace StyleCI\StyleCI\Events;

use Illuminate\Queue\SerializesModels;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the repo was disabled event class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepoWasDisabledEvent
{
    use SerializesModels;

    /**
     * The repo that was disabled.
     *
     * @var \StyleCI\StyleCI\Models\Repo
     */
    protected $repo;

    /**
     * Create a new repo was disabled event instance.
     *
     * @param \StyleCI\StyleCI\Models\Repo $repo
     *
     * @return void
     */
    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get the repo that was disabled.
     *
     * @return \StyleCI\StyleCI\Models\Repo
     */
    public function getRepo()
    {
        return $this->repo;
    }
}
