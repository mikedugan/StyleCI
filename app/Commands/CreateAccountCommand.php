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
 * This is the create account command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class CreateAccountCommand
{
    /**
     * The user name.
     *
     * @var string
     */
    protected $name;

    /**
     * The user email address.
     *
     * @var string
     */
    protected $email;

    /**
     * Create a new create account command instance.
     *
     * @param string $name
     * @param string $email
     *
     * @return void
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Get the user name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
