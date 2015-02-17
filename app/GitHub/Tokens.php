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

namespace StyleCI\StyleCI\GitHub;

use Github\Api\Authorizations;

/**
 * This is the tokens hooks class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Tokens
{
    /**
     * The github authorizations instance.
     *
     * @var \Github\Api\Authorizations
     */
    protected $auth;

    /**
     * The github client id.
     *
     * @var string
     */
    protected $clientId;

    /**
     * Create a new github tokens instance.
     *
     * @param \Github\Api\Authorizations $auth
     * @param string                     $clientId
     *
     * @return void
     */
    public function __construct(Authorizations $auth, $clientId)
    {
        $this->auth = $auth;
        $this->clientId = $clientId;
    }

    /**
     * Revoke the given token.
     *
     * @param string $token
     *
     * @return void
     */
    public function revoke($token)
    {
        $this->auth->revoke($this->clientId, $token);
    }
}
