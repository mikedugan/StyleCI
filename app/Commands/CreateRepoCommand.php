<?php

/*
* This file is part of StyleCI.
*
* (c) Graham Campbell <graham@mineuk.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace StyleCI\StyleCI\Commands;

use StyleCI\StyleCI\Models\Service;

/**
 * This is the create repo command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CreateRepoCommand
{
    /**
     * The repository name.
     *
     * @var string
     */
    protected $name;

    /**
     * The associated service.
     *
     * @var \StyleCI\StyleCI\Models\Service
     */
    protected $service;

    /**
     * Create a new create repo command instance.
     *
     * @param string                          $name
     * @param \StyleCI\StyleCI\Models\Service $service
     *
     * @return void
     */
    public function __construct($name, Service $service)
    {
        $this->name = $name;
        $this->service = $service;
    }

    /**
     * Get the repository name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the associated service.
     *
     * @return \StyleCI\StyleCI\Models\Service
     */
    public function getService()
    {
        return $this->service;
    }
}
