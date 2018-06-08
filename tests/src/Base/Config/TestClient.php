<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 13:53.
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Config;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use ZEROSPAM\Framework\SDK\Client\IOAuthClient;
use ZEROSPAM\Framework\SDK\Client\OAuthClient;
use ZEROSPAM\Framework\SDK\Config\IOAuthConfiguration;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Config\MockOAuthConfiguration;
use ZEROSPAM\Framework\SDK\Test\Base\Util\AccessTokenGenerator;

/**
 * Class TestClient
 *
 * Used to configure the real OAuthClient
 *
 * @package ZEROSPAM\Framework\SDK\Test\Base\Config
 */
class TestClient
{
    /**
     * @var MockHandler
     */
    private $mockHandler;

    /**
     * @var array
     */
    private $container = [];

    /**
     * TestConf constructor.
     *
     * @param MockHandler $mockHandler
     */
    public function __construct(MockHandler $mockHandler)
    {
        $this->mockHandler = HandlerStack::create($mockHandler);
        $this->mockHandler->push(Middleware::history($this->container));
    }

    /**
     * Build the client for this configuration.
     *
     * @return ClientInterface
     */
    public function buildClient(): ClientInterface
    {
        return new Client(
            [
                'handler'  => $this->mockHandler,
                'base_uri' => 'http://127.0.2.1',
            ]
        );
    }

    /**
     * Get client for tests.
     *
     *
     * @return IOAuthClient
     */
    public function getOAuthTestClient(): IOAuthClient
    {
        return new OAuthClient($this->getMockConfiguration(), AccessTokenGenerator::generateAccessToken(), $this->buildClient());
    }

    /**
     * Mock configuration.
     *
     * @return IOAuthConfiguration
     */
    protected function getMockConfiguration(): IOAuthConfiguration
    {
        return new MockOAuthConfiguration(uniqid(), uniqid(), 'http://127.0.3.1', 'http://127.0.0.1');
    }

    /**
     * Containing the request and response done.
     *
     * @see http://docs.guzzlephp.org/en/latest/testing.html#history-middleware
     *
     * @return array
     */
    public function getContainer(): array
    {
        return $this->container;
    }
}
