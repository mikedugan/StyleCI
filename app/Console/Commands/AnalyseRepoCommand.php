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

namespace StyleCI\StyleCI\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This is the analyse repo command class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/StyleCI/StyleCI/blob/master/LICENSE.md> AGPL 3.0
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
