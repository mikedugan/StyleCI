<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Joseph Cohen <joseph.cohen@dinkbit.com>
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
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
     * Handles the request to a raw repo link.
     *
     * @param string $account
     * @param string $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleRedirect($account, $repository)
    {
        return Redirect::to('repos/'.sha1("$account/$repository"));
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
