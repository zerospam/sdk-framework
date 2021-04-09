<?php


namespace ZEROSPAM\Framework\SDK\Test\Tests\Request;


use ZEROSPAM\Framework\SDK\Test\Base\Data\Request\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class RequestUrlTest extends TestCase
{
    public function testValidateUrl()
    {
        $client = $this->preSuccess(['test' => 'data']);
        $request = new TestRequest();
        $client->getOAuthTestClient()->processRequest($request);

        $this->validateUrl($client, 'test');
    }

    public function testvalidateRequestUrl()
    {
        $client = $this->preSuccess(['test' => 'data']);
        $request = new TestRequest();
        $client->getOAuthTestClient()->processRequest($request);

        $this->validateRequestUrl($client, ['tes']);
    }
}
