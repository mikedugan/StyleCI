<?php

/*
 * This file is part of StyleCI.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Tests\StyleCI;

use GrahamCampbell\TestBench\AbstractAppTestCase;

/**
 * This is the abstract test case class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
abstract class AbstractTestCase extends AbstractAppTestCase
{
    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return 'StyleCI\StyleCI\StyleCIServiceProvider';
    }
}
