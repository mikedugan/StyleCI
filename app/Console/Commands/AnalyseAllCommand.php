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
use Illuminate\Foundation\Bus\DispatchesCommands;
use StyleCI\StyleCI\Commands\AnalyseCommitCommand;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the analyse all command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalyseAllCommand extends Command
{
    use DispatchesCommands;

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

        $branches = $this->laravel['styleci.branches']->get($repo->name);

        foreach ($branches as $branch) {
            $commit = $this->getCommit($branch['name'], $repo->id, $branch['commit']);
            $this->dispatch(new AnalyseCommitCommand($commit));
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
        $commit = $this->laravel['styleci.modelfactory']->commit($commit, $repo);

        if (empty($commit->message)) {
            $commit->message = 'Manually run analysis';
        }

        if (empty($commit->ref)) {
            $commit->ref = "refs/heads/$branch";
        }

        $commit->status = 0;
        $commit->save();

        return $commit;
    }
}
