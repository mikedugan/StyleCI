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

namespace StyleCI\StyleCI\Commands;

/**
 * This is the login command class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Joseph Cohen <joseph.cohen@dinkbit.com>
 */
class LoginCommand
{
    /**
     * The user's id.
     *
     * @var string
     */
    protected $id;

    /**
     * The user's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The user's email address.
     *
     * @var string
     */
    protected $email;

    /**
     * The user's access token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new login command instance.
     *
     * @param string $id
     * @param string $name
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function __construct($id, $name, $email, $token)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Get the user's id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the user's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the user's access token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
