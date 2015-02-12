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

namespace StyleCI\StyleCI\Handlers\Events;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use StyleCI\StyleCI\Events\AnalysisHasCompletedEvent;
use StyleCI\StyleCI\GitHub\Collaborators;
use StyleCI\StyleCI\Models\Commit;
use StyleCI\StyleCI\Models\User;

/**
 * This is the analysis notification handler class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AnalysisNotificationsHandler
{
    /**
     * The collaborators instance.
     *
     * @var \StyleCI\StyleCI\GitHub\Collaborators
     */
    protected $collaborators;

    /**
     * The mailer instance.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * Create a new analysis notifications handler instance.
     *
     * @param \StyleCI\StyleCI\GitHub\Collaborators $collaborators
     * @param \Illuminate\Contracts\Mail\Mailer     $mailer
     *
     * @return void
     */
    public function __construct(Collaborators $collaborators, Mailer $mailer)
    {
        $this->collaborators = $collaborators;
        $this->mailer = $mailer;
    }

    /**
     * Handle the analysis has completed event.
     *
     * @param \StyleCI\StyleCI\Events\AnalysisHasCompletedEvent $event
     *
     * @return void
     */
    public function handle(AnalysisHasCompletedEvent $event)
    {
        $commit = $event->getCommit();

        // if the analysis didn't fail, then we don't need to notify anyone
        if ($commit->status !== 2) {
            return;
        }

        $this->sendMessages($commit);
    }

    /**
     * Send out messages to the relevant users.
     *
     * We're emailing all repo collaborators that have accounts on StyleCI.
     *
     * @todo Allow users to set their notification preferences, and support
     * notifying users though other mediums than just email.
     *
     * @param \StyleCI\StyleCI\Models\Commit $commit
     *
     * @return void
     */
    protected function sendMessages(Commit $commit)
    {
        $mail = [
            'repo'    => $commit->name(),
            'commit'  => $commit->message,
            'link'    => route('commit_path', $commit->id),
            'subject' => 'Failed Analysis',
        ];

        foreach (User::whereIn('id', $this->collaborators->get($commit))->get() as $user) {
            $mail['email'] = $user->email;
            $this->mailer->send('emails.failed', $mail, function (Message $message) use ($mail) {
                $message->to($mail['email'])->subject($mail['subject']);
            });
        }
    }
}
