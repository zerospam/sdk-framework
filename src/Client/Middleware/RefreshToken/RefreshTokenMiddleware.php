<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-09-11
 * Time: 10:23
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware\RefreshToken;

use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\Middleware\IRefreshTokenMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\MiddlewareClient;

class RefreshTokenMiddleware implements IRefreshTokenMiddleware
{

    use MiddlewareClient;

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
