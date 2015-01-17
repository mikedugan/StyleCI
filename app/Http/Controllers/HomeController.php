<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Http\Controllers;

use Illuminate\Support\Facades\View;

/**
 * This is the home controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class HomeController extends AbstractController
{
    /**
     * Handles the request to view the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function handle()
    {
        return View::make('index');
    }
}
