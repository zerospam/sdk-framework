<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:31
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils;

use GuzzleHttp\Psr7\Response;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;
use ZEROSPAM\Framework\SDK\Utils\JSON\JSONParsing;

class JsonParsingTest extends TestCase
{

    public function testJsonParsingEmpty(): void
    {
        $empty = new Response();
        $this->assertEquals([], JSONParsing::responseToJson($empty));
    }

    public function testJsonParsingArray(): void
    {
        $empty = new Response(200, [], json_encode(['test' => 'test']));
        $this->assertEquals(['test' => 'test'], JSONParsing::responseToJson($empty));
    }
}
