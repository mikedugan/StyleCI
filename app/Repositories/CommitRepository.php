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

use StyleCI\StyleCI\GitHub\Commits;
use StyleCI\StyleCI\Models\Commit;

/**
 * This is the commit repository class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CommitRepository
{
    /**
     * The commits instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Commits
     */
    protected $commits;

    /**
     * Create a new commit repository instance.
     *
     * @param \StyleCI\StyleCI\GitHub\Commits $commits
     *
     * @return void
     */
    public function __construct(Commits $commits)
    {
        $this->commits = $commits;
    }

    /**
     * Find a commit by its id.
     *
     * @param string $id
     *
     * @return \StyleCI\StyleCI\Models\Commit|null
     */
    public function find($id)
    {
        return Commit::find($id);
    }

    /**
     * Find a commit by its id, or generate a new one.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return \StyleCI\StyleCI\Models\Commit
     */
    public function findOrGenerate($id, array $attributes = [])
    {
        $commit = $this->find($id);

        // if the commit exists, we're done here
        if ($commit) {
            return $commit;
        }

        // otherwise, create a new commit, allowing the id to be overwritten
        return (new Commit())->forceFill(array_merge(['id' => $id], $attributes));
    }

    /**
     * Find a commit by its id for analysis.
     *
     * @param string $id
     * @param string $repo
     * @param string $branch
     *
     * @return \StyleCI\StyleCI\Models\Commit
     */
    public function findForAnalysis($id, $repo, $branch)
    {
        $commit = $this->findOrGenerate($id, ['repo_id' => $repo]);

        if (empty($commit->message)) {
            $commit->message = $this->commits->get($commit)['commit']['message'];
        }

        if (empty($commit->ref)) {
            $commit->ref = "refs/heads/$branch";
        }

        $commit->status = 0;
        $commit->save();

        return $commit;
    }
}
