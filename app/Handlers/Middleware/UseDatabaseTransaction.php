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

namespace StyleCI\StyleCI\Handlers\Middleware;

use Closure;
use Illuminate\Database\DatabaseManager;

/**
 * This is the use database transaction middleware class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class UseDatabaseTransaction
{
    /**
     * The database manager.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $app;

    /**
     * Create a use database transaction middleware instance.
     *
     * @param \Illuminate\Database\DatabaseManager $db
     *
     * @return void
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Handle the command.
     *
     * @param object   $command
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($command, Closure $next)
    {
        return $this->db->transaction(function () use ($command, $next) {
            return $next($command);
        });
    }
}
