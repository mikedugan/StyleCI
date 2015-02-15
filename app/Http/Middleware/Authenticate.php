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

namespace StyleCI\StyleCI\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This is the authenticate middleware class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class Authenticate
{
    /**
     * The authentication guard instance.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * The allowed user ids.
     *
     * @var array
     */
    protected $allowed;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param array                            $allowed
     *
     * @return void
     */
    public function __construct(Guard $auth, array $allowed)
    {
        $this->auth = $auth;
        $this->allowed = $allowed;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->auth->guest()) {
            throw new HttpException(401);
        }

        if (count($this->allowed) > 0 && !in_array($this->auth->user()->id, $this->allowed)) {
            throw new HttpException(403, 'You are not whitelisted.');
        }

        return $next($request);
    }
}
