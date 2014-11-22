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

namespace GrahamCampbell\StyleCI\Http\Controllers;

use GrahamCampbell\StyleCI\Analyser;
use GrahamCampbell\StyleCI\Factories\ModelFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * This is the github controller class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/StyleCI/blob/master/LICENSE.md> AGPL 3.0
 */
class GitHubController extends Controller
{
    /**
     * The analyser instance.
     *
     * @var \GrahamCampbell\StyleCI\Analyser
     */
    protected $analyser;

    /**
     * The model factory instance.
     *
     * @var \GrahamCampbell\StyleCI\Factories\ModelFactory
     */
    protected $factory;

    /**
     * Create a new github controller instance.
     *
     * @param \GrahamCampbell\StyleCI\Analyser               $analyser
     * @param \GrahamCampbell\StyleCI\Factories\ModelFactory $factory
     *
     * @return void
     */
    public function __construct(Analyser $analyser, ModelFactory $factory)
    {
        $this->analyser = $analyser;
        $this->factory = $factory;
    }

    public function handle(Request $request)
    {
        if ($request->header('X-GitHub-Event') === 'push') {
            return $this->handlePush($request->input());
        }

        if ($request->header('X-GitHub-Event') === 'pull_request') {
            return $this->handlePullRequest($request->input());
        }

        if ($request->header('X-GitHub-Event') === 'status') {
            return $this->handleStatus($request->input());
        }

        if ($request->header('X-GitHub-Event') === 'ping') {
            return $this->handlePing();
        }

        return $this->handleOther();
    }

    protected function handlePush(array $input)
    {
        $repo = $this->factory->repo($input['repository']['full_name'])->id;
        $commit = $this->factory->commit($input['head_commit']['id'], $repo);

        if ($input['ref'] === 'refs/heads/master') {
            $commit->main = 1;
        }

        $commit->message = $input['head_commit']['message'];
        $commit->save();

        $this->analyser->prepareAnalysis($commit);

        return new JsonResponse(['message' => 'StyleCI has successfully scheduled the analysis of this event.'], 202, [], JSON_PRETTY_PRINT);
    }

    protected function handlePullRequest(array $input)
    {
        if ($input['action'] === 'opened' && $input['pull_request']['head']['repo']['full_name'] !== $input['pull_request']['base']['repo']['full_name']) {
            $repo = $this->factory->repo($input['repository']['full_name'])->id;
            $commit = $this->factory->commit($input['pull_request']['head']['sha'], $repo);

            $commit->status = 3;
            $commit->save();

            $this->analyser->prepareUpdate($commit);
        }

        return new JsonResponse(['message' => 'StyleCI has successfully recorded the pull request context.'], 200, [], JSON_PRETTY_PRINT);
    }

    protected function handleStatus(array $input)
    {
        if ($input['context'] !== 'continuous-integration/travis-ci') {
            return new JsonResponse(['message' => 'StyleCI has recorded the change, but determined there is nothing for it to do.'], 200, [], JSON_PRETTY_PRINT);
        }

        $repo = $this->factory->repo($input['repository']['full_name'])->id;
        $commit = $this->factory->commit($input['commit']['sha'], $repo);

        if ($input['context'] === 'continuous-integration/travis-ci') {
            if ($input['state'] === 'pending') {
                $commit->travis = 0;
            } elseif ($input['state'] === 'success') {
                $commit->travis = 1;
            } else {
                $commit->travis = 2;
            }
        }

        $commit->save();

        $this->analyser->prepareUpdate($commit);

        return new JsonResponse(['message' => 'StyleCI has recorded the change, and has scheduled analysis of the change.'], 202, [], JSON_PRETTY_PRINT);
    }

    protected function handlePing()
    {
        return new JsonResponse(['message' => 'StyleCI successfully received your ping.'], 200, [], JSON_PRETTY_PRINT);
    }

    protected function handleOther()
    {
        return new JsonResponse(['message' => 'StyleCI does not support this type of event.'], 400, [], JSON_PRETTY_PRINT);
    }
}
