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

$router->get('/', [
    'as'   => 'home',
    'uses' => 'HomeController@handle',
]);

$router->post('github-callback', [
    'as'   => 'webhook_callback',
    'uses' => 'GitHubController@handle',
]);

$router->get('repo/{account}/{repository}', [
    'as'   => 'redirect_path',
    'uses' => 'RepoController@handleRedirect'
]);

$router->get('repos', [
    'as'   => 'repos_path',
    'uses' => 'RepoController@handleList'
]);

$router->get('repos/{repo}', [
    'as'   => 'repo_path',
    'uses' => 'RepoController@handleShow'
]);

$router->get('commits/{commit}', [
    'as'   => 'commit_path',
    'uses' => 'CommitController@handleShow'
]);

$router->get('commits/{commit}/diff', [
    'as'   => 'commit_diff_path',
    'uses' => 'CommitController@handleDiff',
]);

$router->get('commits/{commit}/diff/download', [
    'as'   => 'commit_download_path',
    'uses' => 'CommitController@handleDiffDownload',
]);

$router->group(['prefix' => 'api'], function (Illuminate\Contracts\Routing\Registrar $router) {
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

$router->get('auth/login', [
    'as'   => 'auth_login_path',
    'uses' => 'AuthController@handleLogin',
]);

$router->get('auth/github-callback', [
    'as'   => 'auth_callback_path',
    'uses' => 'AuthController@handleCallback',
]);

$router->get('auth/logout', [
    'as'   => 'auth_logout_path',
    'uses' => 'AuthController@handleLogout',
]);

$router->get('account', [
    'as'   => 'account_path',
    'uses' => 'AccountController@handleShow',
]);
