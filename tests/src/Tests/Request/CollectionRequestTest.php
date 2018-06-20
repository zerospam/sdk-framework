<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-20
 * Time: 11:15
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Request;

use ZEROSPAM\Framework\SDK\Test\Base\Data\Collection\CollectionTestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestResponse;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class CollectionRequestTest extends TestCase
{

    public function testCollectionResponse()
    {
        $json
            = <<<JSON
{
  "response": [
    {
      "author": "Koma",
      "featured": false,
      "id": "chatwork",
      "name": "chatwork",
      "version": "1.0.1",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.svg"
      }
    },
    {
      "author": "Stefan Malzner <stefan@adlk.io>",
      "featured": false,
      "id": "ciscospark",
      "name": "Cisco Spark",
      "version": "1.0.0",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.svg"
      }
    }
  ],
  "meta": {
    "per_page": 2,
    "page": 0,
    "pages": 1,
    "count": 2
  }
}
JSON;

        $client = $this->preSuccess($json);

        $request = new CollectionTestRequest();
        $client->getOAuthTestClient()->processRequest($request);

        $response = $request->getResponse();
        $this->assertCount(2, $response);
        $this->assertArrayNotHasKey(2, $response);
        $this->assertInstanceOf(TestResponse::class, $response[0]);
        $this->assertInstanceOf(TestResponse::class, $response[1]);
        $this->assertEquals("Stefan Malzner <stefan@adlk.io>", $response[1]->author);

        $this->assertEquals(2, $response->getMetaData()->count);
        $this->assertEquals(2, $response->getMetaData()->perPage);
    }

    public function testCollectionResponseIterable()
    {
        $json
            = <<<JSON
{
  "response": [
    {
      "author": "Koma",
      "featured": false,
      "id": "chatwork",
      "name": "chatwork",
      "version": "1.0.1",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.svg"
      }
    },
    {
      "author": "Stefan Malzner <stefan@adlk.io>",
      "featured": false,
      "id": "ciscospark",
      "name": "Cisco Spark",
      "version": "1.0.0",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.svg"
      }
    }
  ],
  "meta": {
    "per_page": 2,
    "page": 0,
    "pages": 1,
    "count": 2
  }
}
JSON;

        $client = $this->preSuccess($json);

        $request = new CollectionTestRequest();
        $client->getOAuthTestClient()->processRequest($request);

        $response = $request->getResponse();
        /** @var TestResponse $testResponse */
        foreach ($response as $testResponse) {
            $this->assertInstanceOf(TestResponse::class, $testResponse);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testModifyCollectionResponse()
    {
        $json
            = <<<JSON
{
  "response": [
    {
      "author": "Koma",
      "featured": false,
      "id": "chatwork",
      "name": "chatwork",
      "version": "1.0.1",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.svg"
      }
    },
    {
      "author": "Stefan Malzner <stefan@adlk.io>",
      "featured": false,
      "id": "ciscospark",
      "name": "Cisco Spark",
      "version": "1.0.0",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.svg"
      }
    }
  ],
  "meta": {
    "per_page": 2,
    "page": 0,
    "pages": 1,
    "count": 2
  }
}
JSON;

        $client = $this->preSuccess($json);

        $request = new CollectionTestRequest();
        $client->getOAuthTestClient()->processRequest($request);

        $response = $request->getResponse();
        $this->assertCount(2, $response);

        $response[0] = new \stdClass();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDeleteCollectionResponse()
    {
        $json
            = <<<JSON
{
  "response": [
    {
      "author": "Koma",
      "featured": false,
      "id": "chatwork",
      "name": "chatwork",
      "version": "1.0.1",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/chatwork/src/icon.svg"
      }
    },
    {
      "author": "Stefan Malzner <stefan@adlk.io>",
      "featured": false,
      "id": "ciscospark",
      "name": "Cisco Spark",
      "version": "1.0.0",
      "icons": {
        "png": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.png",
        "svg": "https://cdn.franzinfra.com/recipes/dist/ciscospark/src/icon.svg"
      }
    }
  ],
  "meta": {
    "per_page": 2,
    "page": 0,
    "pages": 1,
    "count": 2
  }
}
JSON;

        $client = $this->preSuccess($json);

        $request = new CollectionTestRequest();
        $client->getOAuthTestClient()->processRequest($request);

        $response = $request->getResponse();
        $this->assertCount(2, $response);

        unset($response[0]);
    }
}
