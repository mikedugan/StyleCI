<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\StyleCI\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use StyleCI\StyleCI\Analyser;
use StyleCI\StyleCI\Factories\ModelFactory;

/**
 * This is the github controller class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class GitHubController extends Controller
{
    /**
     * The analyser instance.
     *
     * @var \StyleCI\StyleCI\Analyser
     */
    protected $analyser;

    /**
     * The model factory instance.
     *
     * @var \StyleCI\StyleCI\Factories\ModelFactory
     */
    protected $factory;

    /**
     * Create a new github controller instance.
     *
     * @param \StyleCI\StyleCI\Analyser               $analyser
     * @param \StyleCI\StyleCI\Factories\ModelFactory $factory
     *
     * @return void
     */
    public function __construct(Analyser $analyser, ModelFactory $factory)
    {
        $this->analyser = $analyser;
        $this->factory = $factory;
    }

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
            $repo = $this->factory->repo($input['repository']['full_name'])->id;
            $commit = $this->factory->commit($input['head_commit']['id'], $repo);

            if (empty($commit->ref)) {
                $commit->ref = $input['ref'];
            }

            $commit->message = substr(strtok(strtok($input['head_commit']['message'], "\n"), "\r"), 0, 127);
            $commit->save();

            $this->analyser->prepareAnalysis($commit);

            return new JsonResponse(['message' => 'StyleCI has successfully scheduled the analysis of this event.'], 202, [], JSON_PRETTY_PRINT);
        }

        return new JsonResponse(['message' => 'StyleCI has determined that no action is required in this case.'], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Handle opening of a pull request.
     *
     * @param array $input
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handlePullRequest(array $input)
    {
        if (($input['action'] === 'opened' || $input['action'] === 'reopened' || $input['action'] === 'synchronize') && $input['pull_request']['head']['repo']['full_name'] !== $input['pull_request']['base']['repo']['full_name'] && strpos($input['pull_request']['head']['ref'], 'gh-pages') === false) {
            $repo = $this->factory->repo($input['pull_request']['base']['repo']['full_name'])->id;
            $fork = $this->factory->fork($input['pull_request']['head']['repo']['full_name'], $repo)->id;
            $commit = $this->factory->commit($input['pull_request']['head']['sha'], $repo, $fork);

            if (empty($commit->ref)) {
                $commit->ref = $input['pull_request']['head']['ref'];
            }

            $commit->message = substr('Pull Request: '.strtok(strtok($input['pull_request']['title'], "\n"), "\r"), 0, 127);
            $commit->save();

            $this->analyser->prepareAnalysis($commit);

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
