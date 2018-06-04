<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 14:17
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use ZEROSPAM\Framework\SDK\Client\Middleware\Error\AuthenticationMiddleware;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class ClientTest extends TestCase
{

    /**
     * @expectedException \ZEROSPAM\Framework\SDK\Client\Exception\SDKException
     */
    public function testRegisterUnregisterMiddleware(): void
    {
        $client = $this->preFailure([], 401);
        $client->getOAuthTestClient()
               ->registerMiddleware(new AuthenticationMiddleware())
               ->unregisterMiddleware(new AuthenticationMiddleware())
               ->processRequest(new TestRequest());
    }

    public function testResponseAttributeBinding(): void
    {
        $client = $this->preSuccess(['test' => 'data']);

        $request = new TestRequest();
        $client->getOAuthTestClient()
               ->processRequest($request);

        $response = $request->getResponse();
        $this->assertInstanceOf(\stdClass::class, $response->test);
        $this->assertEquals('data', $response->test->test);
    }

    public function testResponseDateBinding(): void
    {
        $now    = Carbon::now()->startOfMinute();
        $client = $this->preSuccess(['test_date' => $now->toIso8601String()]);

        $request = new TestRequest();
        $client->getOAuthTestClient()
               ->processRequest($request);

        $response = $request->getResponse();
        $this->assertInstanceOf(Carbon::class, $response->test_date);
        $this->assertEquals($now, $response->test_date);
    }

    public function testResponseAddedField(): void
    {
        $client = $this->preSuccess(['added' => 'field']);

        $request = new TestRequest();
        $client->getOAuthTestClient()
               ->processRequest($request);

        $response = $request->getResponse();
        $this->assertThat(isset($response->added), $this->isTrue());
        $this->assertEquals('field', $response->get('added'));
    }

    public function testResponseDateBindingNull(): void
    {
        $client = $this->preSuccess([]);

        $request = new TestRequest();
        $client->getOAuthTestClient()
               ->processRequest($request);

        $response = $request->getResponse();
        $this->assertNull($response->test_date);
    }

    public function testRateLimiting(): void
    {
        $client = $this->prepareQueue(
            [
                new Response(
                    200,
                    [
                        'X-RateLimit-Remaining' => 10,
                        'X-RateLimit-Limit'     => 60,
                    ]
                ),
            ]
        );

        $request = new TestRequest();
        $client->getOAuthTestClient()
               ->processRequest($request);

        $response  = $request->getResponse();
        $rateLimit = $response->getRateLimit();
        $this->assertEquals(10, $rateLimit->getRemaining());
        $this->assertEquals(60, $rateLimit->getMaxPerMinute());
    }

    public function testRateLimitingBlocked(): void
    {
        $resetTime = Carbon::now()->addHour()->startOfMinute();
        $client    = $this->prepareQueue(
            [
                new Response(
                    200,
                    [
                        'X-RateLimit-Remaining' => 0,
                        'X-RateLimit-Limit'     => 60,
                        'X-RateLimit-Reset'     => $resetTime->timestamp,
                    ]
                ),
            ]
        );

        $request = new TestRequest();
        $client->getOAuthTestClient()
               ->processRequest($request);

        $response  = $request->getResponse();
        $rateLimit = $response->getRateLimit();
        $this->assertEquals(0, $rateLimit->getRemaining());
        $this->assertEquals(60, $rateLimit->getMaxPerMinute());
        $this->assertEquals($resetTime, $rateLimit->getEndOfThrottle());
    }
}
