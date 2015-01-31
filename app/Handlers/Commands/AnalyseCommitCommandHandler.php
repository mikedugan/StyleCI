<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Joseph Cohen <joseph.cohen@dinkbit.com>
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Handlers\Commands;

use StyleCI\Fixer\Report;
use StyleCI\Fixer\ReportBuilder;
use StyleCI\StyleCI\Commands\AnalyseCommitCommand;
use StyleCI\StyleCI\GitHub\Status;
use StyleCI\StyleCI\Models\Commit;

/**
 * This is the analyse commit command handler.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalyseCommitCommandHandler
{
    /**
     * The report builder instance.
     *
     * @var \StyleCI\Fixer\ReportBuilder
     */
    protected $builder;

    /**
     * The status instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Status
     */
    protected $status;

    /**
     * Create a new analyse commit command handler instance.
     *
     * @param \StyleCI\Fixer\ReportBuilder   $builder
     * @param \StyleCI\StyleCI\GitHub\Status $status
     *
     * @return void
     */
    public function __construct(ReportBuilder $builder, Status $status)
    {
        $this->builder = $builder;
        $this->status = $status;
    }

    /**
     * Handle the command.
     *
     * @param \StyleCI\StyleCI\Commands\AnalyseCommitCommand $command
     *
     * @return void
     */
    public function handle(AnalyseCommitCommand $command)
    {
        $commit = $command->getCommit();

        $report = $this->builder->analyse($commit->name(), $commit->id);

        $this->saveReport($report, $commit);

        $this->pushStatus($commit);
    }

    /**
     * Save the main report to the database.
     *
     * @param \StyleCI\Fixer\Report          $report
     * @param \StyleCI\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    protected function saveReport(Report $report, Commit $commit)
    {
        $commit->time = $report->time();
        $commit->memory = $report->memory();
        $commit->diff = $report->diff();

        if ($report->successful()) {
            $commit->status = 1;
        } else {
            $commit->status = 2;
        }

        $commit->save();
    }

    /**
     * Push the latest status to github.
     *
     * @param \StyleCI\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    public function pushStatus(Commit $commit)
    {
        switch ($commit->status()) {
            case 1:
                $this->status->success($commit->repo->name, $commit->id, $commit->description());
                break;
            case 2:
                $this->status->failure($commit->repo->name, $commit->id, $commit->description());
                break;
            default:
                $this->status->pending($commit->repo->name, $commit->id, $commit->description());
                break;
        }
    }
}
