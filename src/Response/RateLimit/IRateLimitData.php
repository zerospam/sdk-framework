<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-05-31
 * Time: 16:46
 */

namespace ZEROSPAM\Framework\SDK\Response\RateLimit;

use Carbon\Carbon;

/**
 * Class RateLimitData
 *
 * Represent the number of request allowed in a minute
 * How many are still alowed and in the case there is too many done,
 * when  can the next one be done
 *
 * @package ProvulusSDK\Client
 */
interface IRateLimitData
{
    /**
     * Getter for maxPerMinute
     *
     * @return int
     */
    public function getMaxPerMinute(): int;

    /**
     * Getter for currentUsage
     *
     * @return int
     */
    public function getRemaining(): int;

    /**
     * Getter for endOfThrottle
     *
     * @return Carbon
     */
    public function getEndOfThrottle(): Carbon;
}
