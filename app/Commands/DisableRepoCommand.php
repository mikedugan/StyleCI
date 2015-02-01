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

namespace StyleCI\StyleCI\Commands;

use StyleCI\StyleCI\Models\Repo;

/**
 * This is the disable repo command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DisableRepoCommand
{
    /**
     * The repository to delete.
     *
     * @var string
     */
    protected $repo;

    /**
     * Create a new disable repo command instance.
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
     * Get the repository to delete.
     *
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }
}
