<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 14:18
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Data;


use ZEROSPAM\Framework\SDK\Request\Api\BaseRequest;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;

class TestRequest extends BaseRequest
{

    /**
     * The url of the route
     *
     * @return string
     */
    public function routeUrl(): string
    {
        return 'test';
    }

    /**
     * Type of request
     *
     * @return RequestType
     */
    public function httpType(): RequestType
    {
        return RequestType::HTTP_GET();
    }

    /**
     * Process the data that is in the response
     *
     * @param array $jsonResponse
     *
     * @return \ZEROSPAM\Framework\SDK\Response\Api\IResponse
     */
    public function processResponse(array $jsonResponse): IResponse
    {
        return new TestResponse($jsonResponse);
    }
}