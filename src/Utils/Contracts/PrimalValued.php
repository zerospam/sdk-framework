<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:24 PM.
 */

namespace ZEROSPAM\Framework\SDK\Utils\Contracts;

/**
 * Interface PrimalValued
 *
 * Can be reduced to primitive value
 *
 * @package ZEROSPAM\Framework\SDK\Utils\Contracts
 */
interface PrimalValued
{
    /**
     * Return a primitive value for this object.
     *
     * @return int|float|string|float
     */
    public function toPrimitive();
}
