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

use Illuminate\Support\Facades\View;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the repo controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepoController extends AbstractController
{
    /**
     * Create a new account controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'handleList']);
    }

    /**
     * Handles the request to list the repos.
     *
     * @return \Illuminate\View\View
     */
    public function handleList()
    {
        $repos = Repo::orderBy('name', 'asc')->take(50)->get();

        return View::make('repos', compact('repos'));
    }

    /**
     * Handles the request to show a repo.
     *
     * @param \StyleCI\StyleCI\Models\Repo $repo
     *
     * @return \Illuminate\View\View
     */
    public function handleShow(Repo $repo)
    {
        $commits = $repo->commits()->where('ref', 'refs/heads/master')->orderBy('created_at', 'desc')->take(50)->get();

        return View::make('repo', compact('repo', 'commits'));
    }
}
