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

namespace StyleCI\StyleCI\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use StyleCI\StyleCI\Commands\AnalyseCommitCommand;
use StyleCI\StyleCI\Models\Commit;
use StyleCI\StyleCI\Models\Fork;
use StyleCI\StyleCI\Models\Repo;

/**
 * This is the github controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class GitHubController extends AbstractController
{
    /**
     * Handles the request made to StyleCI by the GitHub API.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        if ($request->header('X-GitHub-Event') === 'push') {
            return $this->handlePush($request->input());
        }

        if ($request->header('X-GitHub-Event') === 'pull_request') {
            return $this->handlePullRequest($request->input());
        }

        if ($request->header('X-GitHub-Event') === 'ping') {
            return $this->handlePing();
        }

        return $this->handleOther();
    }

    /**
     * Handle pushing of a branch.
     *
     * @param array $input
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handlePush(array $input)
    {
        if ($input['head_commit'] && strpos($input['ref'], 'gh-pages') === false) {
            $repo = Repo::find($input['repository']['id']);

            if (!$repo) {
                return new JsonResponse(['message' => 'StyleCI cannot analyse this repo because it\'s not enabled on our system.'], 403, [], JSON_PRETTY_PRINT);
            }

            $commitId = $input['head_commit']['id'];
            $commit = Commit::find($commitId);

            if (!$commit) {
                $commit = new Commit(['id' => $commitId, 'repo_id' => $repo->id]);
            }

            if (empty($commit->ref)) {
                $commit->ref = $input['ref'];
            }

            $commit->message = substr(strtok(strtok($input['head_commit']['message'], "\n"), "\r"), 0, 127);
            $commit->status = 0;
            $commit->save();

            $this->dispatch(new AnalyseCommitCommand($commit));

            return new JsonResponse(['message' => 'StyleCI has successfully scheduled the analysis of this event.'], 202, [], JSON_PRETTY_PRINT);
        }

        return new JsonResponse(['message' => 'StyleCI has determined that no action is required in this case.'], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Handle opening of a pull request.
     *
     * Here's we analysing all new commits pushed a pull request ONLY from
     * repos that are not the original. Commits pushed to the original will be
     * analaysed via the push hook instead.
     *
     * @param array $input
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handlePullRequest(array $input)
    {
        if (($input['action'] === 'opened' || $input['action'] === 'reopened' || $input['action'] === 'synchronize') && $input['pull_request']['head']['repo']['full_name'] !== $input['pull_request']['base']['repo']['full_name'] && strpos($input['pull_request']['head']['ref'], 'gh-pages') === false) {
            $repo = Repo::find($input['pull_request']['base']['repo']['id']);

            if (!$repo) {
                return new JsonResponse(['message' => 'StyleCI cannot analyse this repo because it\'s not enabled on our system.'], 403, [], JSON_PRETTY_PRINT);
            }

            $forkId = $input['pull_request']['head']['repo']['id'];
            $forkName = $input['pull_request']['head']['repo']['full_name'];
            $fork = Fork::find($forkId);

            if (!$fork) {
                $fork = new Fork(['id' => $forkId, 'repo_id' => $repo->id, 'name' => $forkName]);
            }

            $commitId = $input['pull_request']['head']['sha'];
            $commit = Commit::find($commitId);

            if (!$commit) {
                $commit = new Commit(['id' => $commitId, 'repo_id' => $repo->id, 'fork_id' => $fork->id]);
            }

            if (empty($commit->ref)) {
                $commit->ref = $input['pull_request']['head']['ref'];
            }

            $commit->message = substr('Pull Request: '.strtok(strtok($input['pull_request']['title'], "\n"), "\r"), 0, 127);
            $commit->status = 0;
            $commit->save();

            $this->dispatch(new AnalyseCommitCommand($commit));

            return new JsonResponse(['message' => 'StyleCI has successfully scheduled the analysis of this event.'], 202, [], JSON_PRETTY_PRINT);
        }

        return new JsonResponse(['message' => 'StyleCI has determined that no action is required in this case.'], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Handle GitHub setup ping.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handlePing()
    {
        return new JsonResponse(['message' => 'StyleCI successfully received your ping.'], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Handle any other kind of input.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleOther()
    {
        return new JsonResponse(['message' => 'StyleCI does not support this type of event.'], 400, [], JSON_PRETTY_PRINT);
    }
}
