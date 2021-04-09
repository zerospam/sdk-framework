<?php


namespace ZEROSPAM\Framework\SDK\Config;


use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IPreRequestMiddleware;

abstract class BaseConfiguration implements IBaseConfiguration
{
    /**
     * @var string
     */
    private $baseUri;

    /**
     * BaseConfiguration constructor.
     * @param string $baseUri
     */
    public function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * End point for Requests.
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @return IMiddleware[]
     */
    public function defaultMiddlewares(): array
    {
        return [];
    }

    /**
     * @return IPreRequestMiddleware[]
     */
    public function defaultPreRequestMiddlewares(): array
    {
        return [];
    }

    /**
     * @param string|null $token
     * @return array
     */
    public function defaultHeaders(string $token = null): array
    {
        return [];
    }
}
