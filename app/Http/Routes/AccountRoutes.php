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

namespace StyleCI\StyleCI\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

/**
 * This is the account routes class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AccountRoutes
{
    /**
     * Define the account routes.
     *
     * @param \Illuminate\Contracts\Routing\Registrar $router
     *
     * @return void
     */
    public function map(Registrar $router)
    {
        $router->post('auth/login', [
            'as'   => 'auth_login_path',
            'uses' => 'AuthController@handleLogin',
        ]);

        $router->get('auth/github-callback', [
            'as'   => 'auth_callback_path',
            'uses' => 'AuthController@handleCallback',
        ]);

        $router->post('auth/logout', [
            'as'   => 'auth_logout_path',
            'uses' => 'AuthController@handleLogout',
        ]);

        $router->get('account', [
            'as'   => 'account_path',
            'uses' => 'AccountController@handleShow',
        ]);

        $router->delete('account/delete', [
            'as'   => 'account_delete_path',
            'uses' => 'AccountController@handleDelete',
        ]);

        $router->get('account/repos', [
            'as'   => 'account_repos_path',
            'uses' => 'AccountController@handleListRepos',
        ]);

        $router->post('account/repos/sync', [
            'as'   => 'account_repos_sync_path',
            'uses' => 'AccountController@handleSync',
        ]);

        $router->post('account/enable/{id}', [
            'as'   => 'enable_repo_path',
            'uses' => 'AccountController@handleEnable',
        ]);

        $router->post('account/disable/{repo}', [
            'as'   => 'disable_repo_path',
            'uses' => 'AccountController@handleDisable',
        ]);
    }
}
