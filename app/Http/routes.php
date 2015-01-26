<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$router->get('/', [
    'as'   => 'home',
    'uses' => 'HomeController@handle',
]);

$router->post('github-callback', [
    'as'   => 'github-callback',
    'uses' => 'GitHubController@handle',
]);

$router->get('repo/{account}/{repository}', [
    'as'   => 'repo-redirect',
    'uses' => 'RepoController@handleRedirect'
]);

$router->get('repos', [
    'as'   => 'list-repos',
    'uses' => 'RepoController@handleList'
]);

$router->get('repos/{repo}', [
    'as'   => 'show-repo',
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
    'uses' => 'AuthController@handleShowLogin',
]);

$router->post('auth/login', [
    'as'   => 'auth_login_path',
    'uses' => 'AuthController@handleLogin',
]);

$router->get('auth/logout', [
    'as'   => 'auth_logout_path',
    'uses' => 'AuthController@handleLogout',
]);

$router->get('auth/signup', [
    'as'   => 'auth_signup_path',
    'uses' => 'AuthController@handleShowSignup',
]);

$router->post('auth/signup', [
    'as'   => 'auth_signup_path',
    'uses' => 'AuthController@handleSignup',
]);

$router->get('auth/connect/{provider}', [
    'as'   => 'auth_connect_path',
    'uses' => 'AuthController@handleRedirect',
]);

$router->get('auth/{provider}/callback', [
    'as'   => 'auth_callback_path',
    'uses' => 'AuthController@handleCallback',
]);
