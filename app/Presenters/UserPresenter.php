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
 * This is the user presenter class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class UserPresenter extends BasePresenter
{
    /**
     * Returns a Gravatar URL for the users email address.
     *
     * @param int $size
     *
     * @return string
     */
    public function gravatar($size = 200)
    {
        return sprintf('https://www.gravatar.com/avatar/%s?size=%d', md5($this->email), $size);
    }
}
