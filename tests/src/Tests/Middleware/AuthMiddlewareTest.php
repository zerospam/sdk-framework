<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:00
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Middleware;

use GuzzleHttp\Psr7\Response;
use ZEROSPAM\Framework\SDK\Client\Middleware\Error\AuthenticationMiddleware;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class AuthMiddlewareTest extends TestCase
{
    public function testAuthMiddlewareRefreshToken(): void
    {
        $testClient = $this->prepareQueue(
            [
                new Response(401),
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            'test' => 'superTest',
                        ]
                    )
                ),
            ]
        );


        $OAuthClient = $testClient->getOAuthTestClient();
        $token       = $OAuthClient->getToken();
        $OAuthClient
            ->registerMiddleware(new AuthenticationMiddleware())
            ->processRequest(new TestRequest());

        $this->assertNotEquals((string)$OAuthClient->getToken(), (string)$token);
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
