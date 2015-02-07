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

namespace StyleCI\StyleCI\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;

/**
 * This is the commit presenter class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CommitPresenter extends BasePresenter
{
    /**
     * Get the commit status summary.
     *
     * @return string
     */
    public function summary()
    {
        switch ($this->wrappedObject->status) {
            case 1:
                return 'PASSED';
            case 2:
                return 'FAILED';
            default:
                return 'PENDING';
        }
    }

    /**
     * Get the commit's repo shorthand id.
     *
     * @return string
     */
    public function shorthandId()
    {
        return substr($this->wrappedObject->id, 0, 6);
    }

    /**
     * Get the commit's style check executed time.
     *
     * @return string
     */
    public function excecutedTime()
    {
        // if analysis is pending, then we don't have a time yet
        if ($this->wrappedObject->status === 0) {
            return '-';
        }

        $time = $this->wrappedObject->time;

        // display the time to 1 dp if less than 10 secs
        if ($time < 10) {
            return number_format(round($time, 1), 1).' sec';
        }

        // display the time to nearest second if less than 2 min
        if ($time < 120) {
            return number_format(round($time, 0)).' sec';
        }

        // display the time to nearest minute otherwise
        return number_format(round($time / 60, 0)).' min';
    }
}
