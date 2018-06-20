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
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestResponse;

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
     * Transform the basic data (string[]) into a response array (IResponse[])
     *
     * @param array $data
     *
     * @return IResponse[]
     */
    public static function dataToResponses(array $data): array
    {
        return array_map(
            function (array $respData) {
                return new TestResponse($respData);
            },
            $data
        );
    }
}
