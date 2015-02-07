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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use StyleCI\StyleCI\GitHub\Repos;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the repo controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepoController extends AbstractController
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

        $this->middleware('auth', ['only' => 'handleList']);
    }

    /**
     * Handles the request to list the repos.
     *
     * @return \Illuminate\View\View
     */
    public function handleList()
    {
        $repos = Repo::whereIn('id', array_keys($this->repos->get($this->auth->user())))->orderBy('name', 'asc')->get();

        $commits = new Collection();

        foreach ($repos as $repo) {
            $commits->put($repo->id, $repo->commits()->where('ref', 'refs/heads/master')->orderBy('created_at', 'desc')->first());
        }

        return View::make('repos', compact('repos', 'commits'));
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
