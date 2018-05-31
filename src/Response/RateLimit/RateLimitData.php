<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-05-31
 * Time: 16:47
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
class RateLimitData implements IRateLimitData
{

    /**
     * @var int
     */
    private $maxPerMinute;

    /**
     * @var int
     */
    private $remaining;

    /**
     * @var Carbon
     */
    private $endOfThrottle;

    /**
     * Getter for maxPerMinute
     *
     * @return int
     */
    public function getMaxPerMinute(): int
    {
        return $this->maxPerMinute;
    }

    /**
     * @param int $maxPerMinute
     *
     * @return $this
     */
    public function setMaxPerMinute(int $maxPerMinute)
    {
        $this->maxPerMinute = $maxPerMinute;

        return $this;
    }

    /**
     * Getter for currentUsage
     *
     * @return int
     */
    public function getRemaining(): int
    {
        return $this->remaining;
    }

    /**
     * @param int $remaining
     *
     * @return $this
     */
    public function setRemaining(int $remaining)
    {
        $this->remaining = $remaining;

        return $this;
    }

    /**
     * Getter for endOfThrottle
     *
     * @return Carbon
     */
    public function getEndOfThrottle(): Carbon
    {
        return $this->endOfThrottle;
    }

    /**
     * @param Carbon|int $endOfThrottle
     *
     * @return $this
     */
    public function setEndOfThrottle($endOfThrottle)
    {
        if (is_int($endOfThrottle)) {
            $endOfThrottle = Carbon::createFromTimestamp($endOfThrottle);
        }

        $this->endOfThrottle = $endOfThrottle;

        return $this;
    }
}
