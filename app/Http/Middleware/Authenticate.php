<?php

/*
* This file is part of StyleCI.
*
* (c) Graham Campbell <graham@mineuk.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace StyleCI\StyleCI\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;

/**
 * This is the authenticate middleware class.
 *
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                throw new Exception('Unauthorized');
            } else {
                return Redirect::guest('auth/login');
            }
        }

        return $next($request);
    }
}
