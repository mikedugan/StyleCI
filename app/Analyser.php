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
use GrahamCampbell\StyleCI\GitHub\Status;
use GrahamCampbell\StyleCI\Models\Commit;
use GrahamCampbell\StyleCI\Tasks\Analyse;
use GrahamCampbell\StyleCI\Tasks\Update;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\Queue;

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
     * @var \GrahamCampbell\StyleCI\GitHub\Status
     */
    protected $status;

    /**
     * The queue instance.
     *
     * @var \Illuminate\Contracts\Queue\Queue
     */
    protected $queue;

    /**
     * The mailer instance.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * The destination email address.
     *
     * @var string
     */
    protected $address;

    /**
     * Create a fixer instance.
     *
     * @param \GrahamCampbell\Fixer\Fixer           $fixer
     * @param \GrahamCampbell\StyleCI\GitHub\Status $status
     * @param \Illuminate\Contracts\Queue\Queue     $queue
     * @param \Illuminate\Contracts\Mail\Mailer     $mailer
     * @param string                                $address
     *
     * @return void
     */
    public function __construct(Fixer $fixer, Status $status, Queue $queue, Mailer $mailer, $address)
    {
        $this->fixer = $fixer;
        $this->status = $status;
        $this->queue = $queue;
        $this->mailer = $mailer;
        $this->address = $address;
    }

    /**
     * Queue the analysis of a commit.
     *
     * @param \GrahamCampbell\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    public function prepareAnalysis(Commit $commit)
    {
        $commit->status = 0;
        $commit->save();

        $this->status->pending($commit->repo->name, $commit->id, $commit->description());

        $this->queue->push(Analyse::class, $commit);
    }

    /**
     * Analyse a commit.
     *
     * @param \GrahamCampbell\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    public function runAnalysis(Commit $commit)
    {
        $report = $this->fixer->analyse($commit->name(), $commit->id);

        $this->saveReport($report, $commit);
        $this->saveFiles($report, $commit);

        $this->pushStatus($commit);
        $this->sendEmails($commit);
    }


    /**
     * Save the main report to the database.
     *
     * @param \GrahamCampbell\Fixer\Report          $report
     * @param \GrahamCampbell\StyleCI\Models\Commit $commit
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
     * Save the file reports to the database.
     *
     * @param \GrahamCampbell\Fixer\Report          $report
     * @param \GrahamCampbell\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    protected function saveFiles(Report $report, Commit $commit)
    {
        $id = $commit->id;
        $files = $commit->files();

        foreach ($report->files() as $file) {
            $files->create([
                'commit_id' => $id,
                'name'      => $file->getName(),
            ]);
        }
    }

    /**
     * Push the latest status to github.
     *
     * @param \GrahamCampbell\StyleCI\Models\Commit $commit
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

    /**
     * Send an any emails that may be required.
     *
     * @param \GrahamCampbell\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    protected function sendEmails(Commit $commit)
    {
        if ($commit->status() === 2) {
            $mail = [
                'repo'    => $commit->repo->name,
                'commit'  => $commit->message,
                'link'    => asset('commits/'.$commit->id),
                'email'   => $this->address,
                'subject' => 'Failed Analysis',
            ];
            $this->mailer->send('emails.failed', $mail, function ($message) use ($mail) {
                $message->to($mail['email'])->subject($mail['subject']);
            });
        }
    }
}
