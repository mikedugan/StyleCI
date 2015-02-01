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

namespace StyleCI\Tests\StyleCI;

use GrahamCampbell\TestBench\Traits\ServiceProviderTestCaseTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTestCaseTrait;

    public function testClientFactoryIsInjectable()
    {
        $this->assertIsInjectable('StyleCI\StyleCI\GitHub\ClientFactory');
    }

    public function testGitHubBranchesIsInjectable()
    {
        $this->assertIsInjectable('StyleCI\StyleCI\GitHub\Branches');
    }

    public function testGitHubStatusIsInjectable()
    {
        $this->assertIsInjectable('StyleCI\StyleCI\GitHub\Status');
    }
}
