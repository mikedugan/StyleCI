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
| and give it the Closure to execute when that URI is requested.
|
*/

$router->get('/', function () {
    return view('index');
});

$router->post('github-callback', 'GitHubController@handle');



$router->get('commits/{commit}', function (GrahamCampbell\StyleCI\Models\Commit $commit) {
    return view('commit', compact('commit'));
});

$router->get('commits/{commit}/diff', function (GrahamCampbell\StyleCI\Models\Commit $commit) {
    return Response::make($commit->diff)->header('Content-Type', 'text/plain; charset=UTF-8');
});
