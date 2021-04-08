<?php

namespace ZEROSPAM\Framework\SDK\Config;

use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IPreRequestMiddleware;

interface IBaseConfiguration
{
    /**
     * End point for Requests.
     *
     * @return string
     */
    public function getBaseUri(): string;

    /**
     * @return IMiddleware[]
     */
    public function defaultMiddlewares(): array;

    /**
     * @return IPreRequestMiddleware[]
     */
    public function defaultPreRequestMiddlewares(): array;

    /**
     * @param string|null $token
     * @return array
     */
    public function defaultHeaders(string $token = null): array;
}
