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
 * This is the account controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AccountController extends AbstractController
{
    /**
     * Create a new account controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'handleShow']);
    }

    /**
     * Show the user's account information.
     *
     * @return \Illuminate\View\View
     */
    public function handleShow()
    {
        return View::make('account');
    }
}
