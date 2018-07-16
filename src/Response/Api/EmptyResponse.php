<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-07-16
 * Time: 09:55
 */

namespace ZEROSPAM\Framework\SDK\Response\Api;

/**
 * Class EmptyResponse
 *
 * Response to represent the code 202
 *
 * @package ZEROSPAM\Framework\SDK\Response\Api
 */
final class EmptyResponse extends BaseResponse
{
    /**
     * EmptyResponse constructor.
     */
    public function __construct()
    {
        parent::__construct([]);
    }
}
