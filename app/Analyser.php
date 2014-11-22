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

namespace GrahamCampbell\StyleCI;

use GrahamCampbell\Fixer\Fixer;
use GrahamCampbell\Fixer\Report;
use GrahamCampbell\StyleCI\Gitub\Status;
use GrahamCampbell\StyleCI\Models\Analysis;
use GrahamCampbell\StyleCI\Models\Commit;

/**
 * This is the analyser class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class Analyser
{
    /**
     * The fixer instance.
     *
     * @var \GrahamCampbell\Fixer\Fixer
     */
    protected $fixer;

    /**
     * The status instance.
     *
     * @var \GrahamCampbell\StyleCI\Gitub\Status
     */
    protected $status;

    /**
     * Create a fixer instance.
     *
     * @param \GrahamCampbell\Fixer\Fixer          $fixer
     * @param \GrahamCampbell\StyleCI\Gitub\Status $status
     *
     * @return void
     */
    public function __construct(Fixer $fixer, Status $status)
    {
        $this->fixer = $fixer;
        $this->status = $status;
    }

    /**
     * Prepare to analyse a commit.
     *
     * Returns the id of the new analysis.
     *
     * @param \GrahamCampbell\StyleCI\Models\Commit $commit
     *
     * @return int
     */
    public function prepare(Commit $commit)
    {
        $analysis = $commit->analyses()->create([]);

        $this->status->pending($commit->repo->name, $commit->id);

        return $analysis->id;
    }

    /**
     * Analyse a commit.
     *
     * @param \GrahamCampbell\StyleCI\Models\Analyse $analysis
     *
     * @return void
     */
    public function analyse(Analysis $analysis)
    {
        $commit = $analysis->commit;
        $repo = $commit->repo;

        $report = $this->fixer->analyse($repo->name, $commit->id);

        $this->saveReport($report, $analysis);
        $this->saveFiles($report, $analysis);

        if ($report->successful()) {
            $this->status->success($repo->name, $commit->id, $analysis->summary);
        } else {
            $this->status->failure($repo->name, $commit->id, $analysis->summary);
        }
    }

    /**
     * Save the main report to the database.
     *
     * @param \GrahamCampbell\Fixer\Report           $report
     * @param \GrahamCampbell\StyleCI\Models\Analyse $analysis
     *
     * @return void
     */
    protected function saveReport(Report $report, Analysis $analysis)
    {
        $analysis->summary = $report->summary();
        $analysis->time = $report->time();
        $analysis->memory = $report->memory();
        $analysis->diff = $report->diff();

        if ($report->successful()) {
            $analysis->status = 1;
        } else {
            $analysis->status = 2;
        }

        $analysis->save();
    }

    /**
     * Save the file reports to the database.
     *
     * @param \GrahamCampbell\Fixer\Report           $report
     * @param \GrahamCampbell\StyleCI\Models\Analyse $analysis
     *
     * @return void
     */
    protected function saveFiles(Report $report, Analysis $analysis)
    {
        $id = $analysis->id;
        $files = $analysis->files();

        foreach ($this->report->files() as $file) {
            $files->create([
                'analysis_id' => $id,
                'name'        => $file->getName(),
                'old'         => $file->getOldBlob()->getContent(),
                'new'         => $file->getNewBlob()->getContent(),
            ]);
        }
    }
}
