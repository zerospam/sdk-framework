<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-20
 * Time: 11:10
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Data\Collection;

use ZEROSPAM\Framework\SDK\Response\Api\Collection\CollectionMetaData;
use ZEROSPAM\Framework\SDK\Response\Api\Collection\CollectionResponse;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Response\TestResponse;

class CollectionTestResponse extends CollectionResponse
{
    /**
     * CollectionTestResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(new CollectionMetaData($data['meta']), $data['response']);
    }

    /**
     * Transform the basic data (string[]) into a response (IResponse)
     *
     * @param array $data
     *
     * @return IResponse
     */
    protected static function dataToResponse(array $data)
    {
        return new TestResponse($data);
    }
}
