<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 14/08/18
 * Time: 9:09 AM
 */

namespace ZEROSPAM\Framework\SDK\Utils\Contracts\Enums;

use MabeEnum\Enum;

/**
 * Interface IEnumInsensitive
 *
 * @package ZEROSPAM\Framework\SDK\Utils\Contracts\Enums
 */
interface IEnumInsensitive
{
    /**
     * Retrieve enumeration instance by its value, case insensitive
     *
     * @param $value
     *
     * @return Enum
     */
    public static function byValueInsensitive(string $value);

    /**
     * Retrieve enumeration instance by name, case insensitive
     *
     * @param string $name
     *
     * @return Enum
     */
    public static function byNameInsensitive(string $name);

    /**
     * String representing the different values of enumeration
     *
     * @param string $sep
     *
     * @return string
     */
    public static function getDisplayableValues($sep = ', '): string;

    /**
     * Return an array of enum matching all the given names
     *
     * @param string[] $names
     *
     * @return self[]
     */
    public static function getEnumsByNameInsensitive(array $names): array;
}