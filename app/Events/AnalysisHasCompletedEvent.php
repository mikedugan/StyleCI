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

use StyleCI\StyleCI\Models\Commit;

/**
 * This is the analysis has completed event class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalysisHasCompletedEvent
{
    /**
     * The commit that was analysed.
     *
     * @var \StyleCI\StyleCI\Models\Commit
     */
    protected $commit;

    /**
     * Create a new analysis has completed event instance.
     *
     * @param \StyleCI\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    public function __construct(Commit $commit)
    {
        $this->commit = $commit;
    }

    /**
     * Get the commit that was analysed.
     *
     * @return \StyleCI\StyleCI\Models\Commit
     */
    public function getCommit()
    {
        return $this->commit;
    }
}
