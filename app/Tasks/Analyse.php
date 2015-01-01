<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Tasks;

use Illuminate\Contracts\Queue\Job;
use StyleCI\StyleCI\Analyser;
use StyleCI\StyleCI\Models\Commit;

/**
 * This is the analyse task class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Analyse
{
    /**
     * The analyser instance.
     *
     * @var \StyleCI\StyleCI\Analyser
     */
    protected $analyser;

    /**
     * Create a task instance.
     *
     * @param \StyleCI\StyleCI\Analyser $analyser
     *
     * @return void
     */
    public function __construct(Analyser $analyser)
    {
        $this->analyser = $analyser;
    }

    /**
     * Kick of the analysis.
     *
     * @param \Illuminate\Contracts\Queue\Job $job
     * @param \StyleCI\StyleCI\Models\Commit  $commit
     *
     * @return void
     */
    public function fire(Job $job, Commit $commit)
    {
        $this->analyser->runAnalysis($commit);

        $job->delete();
    }
}
