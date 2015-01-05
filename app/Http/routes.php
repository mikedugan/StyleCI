<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Contracts\Routing\Registrar;

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
    $repos = StyleCI\StyleCI\Models\Repo::orderBy('name', 'asc')->take(50)->get();

    return view('repos', compact('repos'));
});

$router->get('repos/{repo}', function (StyleCI\StyleCI\Models\Repo $repo) {
    $commits = $repo->commits()->where('ref', 'refs/heads/master')->orderBy('created_at', 'desc')->take(50)->get();

    return view('repo', compact('repo', 'commits'));
});

$router->get('commits/{commit}', function (StyleCI\StyleCI\Models\Commit $commit) {
    return view('commit', compact('commit'));
});

$router->get('commits/{commit}/diff', function (StyleCI\StyleCI\Models\Commit $commit) {
    return Response::make($commit->diff)->header('Content-Type', 'text/plain; charset=UTF-8');
});

$router->get('commits/{commit}/diff/download', function (StyleCI\StyleCI\Models\Commit $commit) {
    return Response::make($commit->diff)
        ->header('Content-Type', 'text/plain; charset=UTF-8')
        ->header('Content-Disposition', 'attachment; filename=patch.txt');
});

$router->group(['prefix' => 'api'], function (Registrar $router) {
    $router->get('repo/{account}/{repository}', function ($account, $repository) {
        if ($repo = StyleCI\StyleCI\Models\Repo::find(sha1("$account/$repository"))) {
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
