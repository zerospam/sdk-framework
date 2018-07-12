<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-07-12
 * Time: 09:50
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Request;

use ZEROSPAM\Framework\SDK\Test\Base\Request\NullableTestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class NullableRequestTest extends TestCase
{

    /**
     *
     */
    public function testNullFieldInRequestSet(): void
    {
        $client = $this->preSuccess([]);

        $request = new NullableTestRequest();
        $request->setNullField(null);

        $client->getOAuthTestClient()->processRequest($request);

        $this->validateRequest($client, ['null_field' => null]);
    }

    /**
     *
     */
    public function testNullFieldInRequestNotSet(): void
    {
        $client = $this->preSuccess([]);

        $request = new NullableTestRequest();

        $client->getOAuthTestClient()->processRequest($request);

        $this->validateRequest($client, []);
    }
}
