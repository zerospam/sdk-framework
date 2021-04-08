<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:13.
 */

namespace ZEROSPAM\Framework\SDK\Client\OAuth;

use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\IClient;
use ZEROSPAM\Framework\SDK\Client\Middleware\IRefreshTokenMiddleware;

/**
 * Interface IOAuthClient
 *
 * Client to make request to a server that use OAuth2 authentication
 *
 * @package ZEROSPAM\Framework\SDK\Client
 */
interface IOAuthClient extends IClient
{
    /**
     * Refresh token.
     */
    public function refreshToken(): AccessToken;

    /**
     * Currently used access token.
     *
     * @return AccessToken
     */
    public function getToken(): AccessToken;

    /**
     * Set the AccessToken
     *
     * @param AccessToken $token
     */
    public function setToken(AccessToken $token): void;

    /**
     * Register a middleware to take care of refresh token
     *
     * @param IRefreshTokenMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function registerRefreshTokenMiddleware(IRefreshTokenMiddleware $middleware): IOAuthClient;

    /**
     * UnRegister a refreshToken middleware
     *
     * @param IRefreshTokenMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function unregisterRefreshTokenMiddleware(IRefreshTokenMiddleware $middleware): IOAuthClient;
}
