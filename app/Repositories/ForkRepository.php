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

namespace StyleCI\StyleCI\Repositories;

use StyleCI\StyleCI\Models\Fork;

/**
 * This is the fork repository class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ForkRepository
{
    /**
     * Find a fork by its id.
     *
     * @param string $id
     *
     * @return \StyleCI\StyleCI\Models\Fork|null
     */
    public function find($id)
    {
        return Fork::find($id);
    }

    /**
     * Find a fork by its id, or generate a new one.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return \StyleCI\StyleCI\Models\Fork
     */
    public function findOrGenerate($id, array $attributes = [])
    {
        $fork = $this->find($id);

        // if the fork exists, we're done here
        if ($fork) {
            return $fork;
        }

        // otherwise, create a new fork, allowing the id to be overwritten
        return (new Fork())->forceFill(array_merge(['id' => $id], $attributes));
    }
}
