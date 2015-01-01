<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This is the analyse repo command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalyseRepoCommand extends Command
{
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
    public function fire()
    {
        $repo = $this->argument('repo');

        $this->comment('Getting the list of branches for "'.$repo.'".');

        $branches = $this->laravel['styleci.branches']->get($repo);

        foreach ($branches as $branch) {
            $commit = $this->getCommit($branch['name'], $repo, $branch['commit']);
            $this->laravel['styleci.analyser']->prepareAnalysis($commit);
            $this->info('Analysis of the "'.$branch['name'].'" branch has been scheduled.');
        }
    }

    /**
     * Get the commit model.
     *
     * @param string $branch
     * @param string $repo
     * @param string $commit
     *
     * @return \StyleCI\StyleCI\Models\Commit
     */
    protected function getCommit($branch, $repo, $commit)
    {
        $repo = $this->laravel['styleci.modelfactory']->repo($repo)->id;

        $commit = $this->laravel['styleci.modelfactory']->commit($commit, $repo);

        if (empty($commit->message)) {
            $commit->message = "Manually run analysis";
        }

        if (empty($commit->ref)) {
            $commit->ref = "refs/heads/$branch";
        }

        $commit->status = 0;
        $commit->save();

        return $commit;
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
