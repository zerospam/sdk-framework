<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 14:17
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests;

use Carbon\Carbon;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class ClientTest extends TestCase
{
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
}
