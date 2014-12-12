<?php

/**
 * This file is part of StyleCI by Graham Campbell.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$router->get('/', function () {
    return view('index');
});

$router->post('github-callback', 'GitHubController@handle');

$router->get('repo/{account}/{repository}', function ($account, $repository) {
    return Redirect::to('repos/'.sha1("$account/$repository"));
});

$router->get('repos', function () {
    $repos = GrahamCampbell\StyleCI\Models\Repo::orderBy('name', 'asc')->take(50)->get();

    return view('repos', compact('repos'));
});

$router->get('repos/{repo}', function (GrahamCampbell\StyleCI\Models\Repo $repo) {
    $commits = $repo->commits()->where('ref', 'refs/heads/master')->orderBy('created_at', 'desc')->take(50)->get();

    return view('repo', compact('repo', 'commits'));
});

$router->get('commits/{commit}', function (GrahamCampbell\StyleCI\Models\Commit $commit) {
    return view('commit', compact('commit'));
});

$router->get('commits/{commit}/diff', function (GrahamCampbell\StyleCI\Models\Commit $commit) {
    return Response::make($commit->diff)->header('Content-Type', 'text/plain; charset=UTF-8');
});

$router->group(['prefix' => 'api'], function($router) {
    $router->get('repo/{account}/{repository}', function ($account, $repository) {
        if ($repo = GrahamCampbell\StyleCI\Models\Repo::find(sha1("$account/$repository"))) {
            if ($commit = $repo->commits()->where('ref', 'refs/heads/master')->orderBy('created_at', 'desc')->first()) {
                return new Illuminate\Http\JsonResponse(['message' => $commit->description().'.', 'status' => $commit->status()], 200, [], JSON_PRETTY_PRINT);
            } else {
                return new Illuminate\Http\JsonResponse(['message' => 'StyleCI has not analysed the master branch of the requested repository yet.'], 400, [], JSON_PRETTY_PRINT);
            }
        } else {
            return new Illuminate\Http\JsonResponse(['message' => 'StyleCI does not have the requested repository on record.'], 404, [], JSON_PRETTY_PRINT);
        }
    });
});
