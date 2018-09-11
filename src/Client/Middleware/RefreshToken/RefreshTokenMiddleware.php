<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-09-11
 * Time: 10:23
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware\RefreshToken;

use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\IOAuthClient;
use ZEROSPAM\Framework\SDK\Client\Middleware\IRefreshTokenMiddleware;

class RefreshTokenMiddleware implements IRefreshTokenMiddleware
{

    /**
     * @var IOAuthClient
     */
    protected $client;

    /**
     * Set the OAuth Client.
     *
     * @param IOAuthClient $client
     *
     * @return $this
     */
    public function setClient(IOAuthClient $client): IRefreshTokenMiddleware
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Take care of refreshing the token
     *
     * @param AccessToken $previousToken
     *
     * @return AccessToken
     */
    public function handleRefreshToken(AccessToken $previousToken): AccessToken
    {
        return $this->client->getConfiguration()->refreshToken($previousToken->getRefreshToken());
    }
}
