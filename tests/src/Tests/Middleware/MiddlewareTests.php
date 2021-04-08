<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:00.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Middleware;

use GuzzleHttp\Psr7\Response;
use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\Middleware\Error\AuthenticationMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IPreRequestMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IRefreshTokenMiddleware;
use ZEROSPAM\Framework\SDK\Client\OAuth\IOAuthClient;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Request\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Response\TestResponse;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;
use ZEROSPAM\Framework\SDK\Test\Base\Util\AccessTokenGenerator;

class MiddlewareTests extends TestCase
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

    public function testPreRequestMiddleware(): void
    {
        $client  = $this->preSuccess([]);
        $request = new TestRequest();

        $mock = \Mockery::mock(IPreRequestMiddleware::class)
                        ->shouldReceive('handle')
                        ->once()
                        ->andReturnUndefined();

        $client->getOAuthTestClient()
               ->registerPreRequestMiddleware($mock->getMock())
               ->processRequest($request);

        $this->assertInstanceOf(TestRequest::class, $request);
    }

    public function testRefreshTokenMiddleware(): void
    {
        $client          = $this->preSuccess([]);
        $OAuthTestClient = $client->getOAuthTestClient();

        $token = new AccessToken(['access_token' => '45564', 'refresh_token' => "test"]);
        $mock  = \Mockery::mock('alias:' . IRefreshTokenMiddleware::class)
                         ->shouldReceive('handleRefreshToken')
                         ->once()
                         ->andReturn($token)
                         ->getMock()
                         ->shouldReceive('setClient')
                         ->once()
                         ->andReturnSelf();


        $OAuthTestClient
            ->registerRefreshTokenMiddleware($mock->getMock())
            ->refreshToken();

        $this->assertSame($token, $OAuthTestClient->getToken());
    }
}
