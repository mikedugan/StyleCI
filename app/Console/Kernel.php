<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * This is the console kernel class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var string[]
     */
    protected $commands = [
        Commands\AnalyseAllCommand::class,
        Commands\AnalyseRepoCommand::class,
    ];
}
