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

$router->get('account/delete', [
    'as'   => 'account_delete_path',
    'uses' => 'AccountController@handleDelete',
]);

$router->get('account/repos', [
    'as'   => 'account_repos_path',
    'uses' => 'AccountController@handleListRepos',
]);

$router->get('account/enable/{id}', [
    'as'   => 'enable_repo_path',
    'uses' => 'AccountController@handleEnable',
]);

$router->get('account/disable/{repo}', [
    'as'   => 'disable_repo_path',
    'uses' => 'AccountController@handleDisable',
]);
