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

namespace StyleCI\StyleCI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesCommands;
use StyleCI\StyleCI\Commands\AnalyseCommitCommand;
use StyleCI\StyleCI\GetCommitTrait;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the analyse all command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalyseAllCommand extends Command
{
    use DispatchesCommands, GetCommitTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'analyse:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse all the heads of every repo';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $repos = Repo::orderBy('name', 'asc')->get();

        foreach ($repos as $repo) {
            $this->analyse($repo);
        }
    }

    /**
     * Analyse all the branches on a repo.
     *
     * @param \StyleCI\StyleCI\Models\Repo $repo
     *
     * @return void
     */
    protected function analyse(Repo $repo)
    {
        $this->comment('Getting the list of branches for "'.$repo->name.'".');

        $branches = $this->laravel['styleci.branches']->get($repo);

        foreach ($branches as $branch) {
            $commit = static::getCommit($branch['name'], $repo->id, $branch['commit']);
            $this->dispatch(new AnalyseCommitCommand($commit));
            $this->info('Analysis of the "'.$branch['name'].'" branch has been scheduled.');
        }
    }
}
