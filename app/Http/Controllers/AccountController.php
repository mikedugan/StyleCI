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

namespace StyleCI\StyleCI\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use StyleCI\StyleCI\Commands\DisableRepoCommand;
use StyleCI\StyleCI\Commands\EnableRepoCommand;
use StyleCI\StyleCI\GitHub\Repos;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the account controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AccountController extends AbstractController
{
    /**
     * The authentication guard instance.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * The github repos instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Repos
     */
    protected $repos;

    /**
     * Create a new account controller instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \StyleCI\StyleCI\GitHub\Repos    $repos
     *
     * @return void
     */
    public function __construct(Guard $auth, Repos $repos)
    {
        $this->auth = $auth;
        $this->repos = $repos;

        $this->middleware('auth');
    }

    /**
     * Show the user's account information.
     *
     * @return \Illuminate\View\View
     */
    public function handleShow()
    {
        $repos = $this->repos->get($this->auth->user());

        return View::make('account')->withRepos($repos);
    }

    /**
     * Enable StyleCI for a repo.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function handleEnable($id)
    {
        $name = $this->repos->get($this->auth->user())[$id]['name'];

        $this->dispatch(new EnableRepoCommand($id, $name, $this->auth->user()));

        return Redirect::route('account_path');
    }

    /**
     * Disable StyleCI for a repo.
     *
     * @param \StyleCI\StyleCI\Models\Repo $repo
     *
     * @return \Illuminate\Http\Response
     */
    public function handleDisable(Repo $repo)
    {
        $this->dispatch(new DisableRepoCommand($repo));

        return Redirect::route('account_path');
    }
}
