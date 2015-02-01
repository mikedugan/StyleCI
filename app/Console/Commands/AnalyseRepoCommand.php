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
use StyleCI\StyleCI\Models\Repo;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This is the analyse repo command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalyseRepoCommand extends Command
{
    use DispatchesCommands, GetCommitTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'analyse:repo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse all the heads of a repo';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $repo = Repo::findOrFail(sha1($this->argument('repo')));

        $this->comment('Getting the list of branches for "'.$repo->name.'".');

        $branches = $this->laravel['styleci.branches']->get($repo);

        foreach ($branches as $branch) {
            $commit = $this->getCommit($branch['name'], $repo->id, $branch['commit']);
            $this->dispatch(new AnalyseCommitCommand($commit));
            $this->info('Analysis of the "'.$branch['name'].'" branch has been scheduled.');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['repo', InputArgument::REQUIRED, 'The repo to analyse'],
        ];
    }
}
