<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-09-11
 * Time: 10:21
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware;

use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\IOAuthClient;

interface IRefreshTokenMiddleware
{

    /**
     * Set the OAuth Client.
     *
     * @param IOAuthClient $client
     *
     * @return $this
     */
    public function setClient(IOAuthClient $client): self;

    /**
     * Take care of refreshing the token
     *
     * @param AccessToken $previousToken
     *
     * @param int         $tries
     *
     * @return AccessToken
     */
    public function handleRefreshToken(AccessToken $previousToken, int $tries): AccessToken;
}
