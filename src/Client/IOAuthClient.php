<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:13.
 */

namespace ZEROSPAM\Framework\SDK\Client;

use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IPreRequestMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IRefreshTokenMiddleware;
use ZEROSPAM\Framework\SDK\Config\IOAuthConfiguration;
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;

/**
 * Interface IOAuthClient
 *
 * Client to make request to a server that use OAuth2 authentication
 *
 * @package ZEROSPAM\Framework\SDK\Client
 */
interface IOAuthClient
{
    /**
     * Register the given middleware.
     *
     * @param \ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware $middleware
     *
     * @return $this
     */
    public function registerMiddleware(IMiddleware $middleware): self;

    /**
     * Unregister the middleware.
     *
     * In fact, all middleware having the same class
     *
     * @param \ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function unregisterMiddleware(IMiddleware $middleware): self;

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
     * Process the given request and return an array containing the results.
     *
     * @param IRequest $request
     *
     * @return IResponse
     */
    public function processRequest(IRequest $request): IResponse;

    /**
     * Register a pre request middleware
     *
     * @param IPreRequestMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function registerPreRequestMiddleware(IPreRequestMiddleware $middleware): IOAuthClient;

    /**
     * UnRegister a pre request middleware
     *
     * @param IPreRequestMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function unregisterPreRequestMiddleware(IPreRequestMiddleware $middleware): IOAuthClient;

    /**
     * Set the AccessToken
     *
     * @param AccessToken $token
     */
    public function setToken(AccessToken $token): void;

    /**
     * Get linked configuration
     *
     * @return IOAuthConfiguration
     */
    public function getConfiguration(): IOAuthConfiguration;

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
     * @param IPreRequestMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function unregisterRefreshTokenMiddleware(IRefreshTokenMiddleware $middleware): IOAuthClient;
}
