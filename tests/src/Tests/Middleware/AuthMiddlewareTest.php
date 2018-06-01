<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:00
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Middleware;

use GuzzleHttp\Psr7\Response;
use ZEROSPAM\Framework\SDK\Client\IOAuthClient;
use ZEROSPAM\Framework\SDK\Client\Middleware\Error\AuthenticationMiddleware;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestResponse;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;
use ZEROSPAM\Framework\SDK\Test\Base\Util\AccessTokenGenerator;

class AuthMiddlewareTest extends TestCase
{
    public function testAuthMiddlewareRefreshToken(): void
    {
        $testClient = $this->prepareQueue(
            [
                new Response(401),
            ]
        );


        $OAuthClient = $testClient->getOAuthTestClient();
        $middleware  = new AuthenticationMiddleware();


        $OAuthClient
            ->registerMiddleware($middleware);
        $mockClient = \Mockery::mock(IOAuthClient::class)
                              ->shouldReceive('refreshToken')
                              ->once()
                              ->andReturn(AccessTokenGenerator::generateAccessToken())
                              ->getMock()
                              ->shouldReceive('processRequest')
                              ->once()
                              ->andReturn(new TestResponse(['test' => 'data']))
                              ->getMock();

        $middleware->setClient($mockClient);

        $this->assertInstanceOf(TestResponse::class, $OAuthClient->processRequest(new TestRequest()));
    }

    /**
     * @expectedException \ZEROSPAM\Framework\SDK\Client\Exception\TooManyRetriesException
     */
    public function testAuthMiddlewareGiveUpAfterTooManyRetries(): void
    {
        $testClient = $this->prepareQueue(
            [
                new Response(401),
                new Response(401),
                new Response(401),
                new Response(401),
            ]
        );


        $OAuthClient = $testClient->getOAuthTestClient();
        $OAuthClient
            ->registerMiddleware(new AuthenticationMiddleware())
            ->processRequest(new TestRequest());
    }
}
