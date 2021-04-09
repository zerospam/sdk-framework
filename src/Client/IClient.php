<?php


namespace ZEROSPAM\Framework\SDK\Client;


use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IPreRequestMiddleware;
use ZEROSPAM\Framework\SDK\Config\BaseConfiguration;
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;

interface IClient
{
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
     * @return self
     */
    public function registerPreRequestMiddleware(IPreRequestMiddleware $middleware): self;

    /**
     * UnRegister a pre request middleware
     *
     * @param IPreRequestMiddleware $middleware
     *
     * @return self
     */
    public function unregisterPreRequestMiddleware(IPreRequestMiddleware $middleware): self;

    /**
     * Unregister the middleware.
     *
     * @param IMiddleware $middleware
     *
     * @return self
     */
    public function unregisterMiddleware(IMiddleware $middleware): self;

    /**
     * Register the given middleware.
     *
     * @param IMiddleware $middleware
     *
     * @return self
     */
    public function registerMiddleware(IMiddleware $middleware): self;

    /**
     * @return BaseConfiguration
     */
    public function getConfiguration(): BaseConfiguration;
}
