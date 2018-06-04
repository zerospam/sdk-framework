<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:57 PM.
 */

namespace ZEROSPAM\Framework\SDK\Utils\Contracts;

/**
 * Interface Arrayable.
 *
 * Can be transform into an array
 */
interface Arrayable
{
    /**
     * Return the object as Array.
     *
     * @return array
     */
    public function toArray(): array;
}
