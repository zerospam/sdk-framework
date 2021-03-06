<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:23 PM.
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments;

use ZEROSPAM\Framework\SDK\Utils\Contracts\PrimalValued;

/**
 * Interface IArgument
 *
 * Argument used in the request
 *
 * @package ZEROSPAM\Framework\SDK\Request\Arguments
 */
interface IArgument extends PrimalValued
{
    /**
     * Key for the argument.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Possible Subkey of the argument
     *
     * @return string
     */
    public function getSubKey(): string;
}
