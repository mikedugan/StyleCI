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

/**
 * This is the sign up command class.
 *
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class SignupCommand
{
    /**
     * User name.
     *
     * @var string
     */
    public $name;

    /**
     * User email.
     *
     * @var string
     */
    public $email;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
}
