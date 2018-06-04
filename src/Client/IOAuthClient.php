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
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;

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
}
