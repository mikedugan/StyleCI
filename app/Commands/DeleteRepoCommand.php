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

/**
 * This is the delete repo command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class DeleteRepoCommand
{
    /**
     * The repository to delete.
     *
     * @var string
     */
    protected $repo;

    /**
     * Create a new delete repo command instance.
     *
     * @param string $name
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
