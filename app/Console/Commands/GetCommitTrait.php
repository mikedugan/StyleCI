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

namespace StyleCI\StyleCI\Console\Commands;

use StyleCI\StyleCI\Models\Commit;

/**
 * This is the get commit trait.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
trait GetCommitTrait
{
    /**
     * Get the commit model.
     *
     * @param string $branch
     * @param string $repo
     * @param string $commit
     *
     * @return \StyleCI\StyleCI\Models\Commit
     */
    protected function getCommit($branch, $repo, $commit)
    {
        $commit = Commit::findOrNew($commit, ['id' => $commit, 'repo_id' => $repo]);

        if (empty($commit->message)) {
            $commit->message = 'Manually run analysis';
        }

        if (empty($commit->ref)) {
            $commit->ref = "refs/heads/$branch";
        }

        $commit->status = 0;
        $commit->save();

        return $commit;
    }
}
