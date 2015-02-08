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

namespace StyleCI\StyleCI\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;

/**
 * This is the current user composer class.
 *
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CurrentUrlComposer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\Contracts\View\View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currentUrl', URL::full());
    }
}
