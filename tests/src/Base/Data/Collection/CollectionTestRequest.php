<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-20
 * Time: 11:09
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Data\Collection;

use ZEROSPAM\Framework\SDK\Request\Api\BaseRequest;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;

/**
 * Class CollectionTestRequest
 *
 * @method CollectionTestResponse getResponse()
 * @package ZEROSPAM\Framework\SDK\Test\Base\Data\Collection
 */
class CollectionTestRequest extends BaseRequest
{


    /**
     * The url of the route.
     *
     * @return string
     */
    public function routeUrl(): string
    {
        return 'collection';
    }

    /**
     * Type of request.
     *
     * @return RequestType
     */
    public function httpType(): RequestType
    {
        return RequestType::HTTP_GET();
    }

    /**
     * Process the data that is in the response.
     *
     * @param array $jsonResponse
     *
     * @return \ZEROSPAM\Framework\SDK\Response\Api\IResponse
     */
    public function processResponse(array $jsonResponse): IResponse
    {
        return new CollectionTestResponse($jsonResponse);
    }
}
