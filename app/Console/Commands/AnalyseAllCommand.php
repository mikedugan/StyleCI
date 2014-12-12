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

namespace GrahamCampbell\StyleCI\Console\Commands;

use GrahamCampbell\StyleCI\Models\Repo;
use Illuminate\Console\Command;

/**
 * This is the analyse all command class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class AnalyseAllCommand extends Command
{
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
    public function fire()
    {
        $repos = Repo::orderBy('name', 'asc')->get();

        foreach ($repos as $repo) {
            $this->analyse($repo);
        }
    }

    /**
     * Analyse all the branches on a repo.
     *
     * @param \GrahamCampbell\StyleCI\Models\Repo $repo
     *
     * @return void
     */
    protected function analyse(Repo $repo)
    {
        $this->comment('Getting the list of branches for "'.$repo->name.'".');

        $branches = $this->laravel['styleci.branches']->get($repo->name);

        foreach ($branches as $branch) {
            $commit = $this->getCommit($branch['name'], $repo->id, $branch['commit']);
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
     * @return \GrahamCampbell\StyleCI\Models\Commit
     */
    protected function getCommit($branch, $repo, $commit)
    {
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
}
