<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-18
 * Time: 11:38
 */

namespace ZEROSPAM\Framework\SDK\Response\Api\Collection;

use ZEROSPAM\Framework\SDK\Response\Api\BaseResponse;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;

/**
 * Class CollectionResponse
 *
 * Represent a response that contains more Responses
 * @method IResponse[] data
 *
 * @package ZEROSPAM\Framework\SDK\Response\Api\Collection
 */
abstract class CollectionResponse extends BaseResponse
{

    /**
     * @var CollectionMetaData
     */
    private $metaData;

    /**
     * CollectionResponse constructor.
     *
     * @param CollectionMetaData $metaData
     * @param string[]           $data
     */
    public function __construct(CollectionMetaData $metaData, array $data)
    {
        $this->metaData = $metaData;
        parent::__construct(static::dataToResponses($data));
    }

    /**
     * Transform the basic data (string[]) into a response array (IResponse[])
     *
     * @param array $data
     *
     * @return IResponse[]
     */
    abstract public static function dataToResponses(array $data): array;

    /**
     * Meta data of the collection (pagination mostly)
     *
     * @return CollectionMetaData
     */
    public function getMetaData(): CollectionMetaData
    {
        return $this->metaData;
    }
}
