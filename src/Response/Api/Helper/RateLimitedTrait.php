<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-07-16
 * Time: 10:22
 */

namespace ZEROSPAM\Framework\SDK\Response\Api\Helper;

use ZEROSPAM\Framework\SDK\Response\Api\IRateLimitedResponse;
use ZEROSPAM\Framework\SDK\Response\RateLimit\RateLimitData;

trait RateLimitedTrait
{
    /**
     * @var RateLimitData
     */
    protected $rateLimit;

    /**
     * @return RateLimitData
     */
    public function getRateLimit(): RateLimitData
    {
        return $this->rateLimit;
    }

    /**
     * @param RateLimitData $rateLimit
     *
     * @return $this
     */
    public function setRateLimit(RateLimitData $rateLimit): IRateLimitedResponse
    {
        $this->rateLimit = $rateLimit;

        return $this;
    }
}
