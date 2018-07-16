<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-07-16
 * Time: 10:21
 */

namespace ZEROSPAM\Framework\SDK\Response\Api;

use ZEROSPAM\Framework\SDK\Response\RateLimit\RateLimitData;


/**
 * Class BaseResponse
 *
 * Reponse given by the client when processing a request.
 * Take care of providing generator for bindings
 *
 * @package ZEROSPAM\Framework\SDK\Response\Api
 */
interface IRateLimitedResponse extends IResponse
{
    /**
     * @return RateLimitData
     */
    public function getRateLimit(): RateLimitData;

    /**
     * @param RateLimitData $rateLimit
     *
     * @return $this
     */
    public function setRateLimit(RateLimitData $rateLimit): IRateLimitedResponse;
}