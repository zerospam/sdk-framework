<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 14/08/18
 * Time: 9:08 AM
 */

namespace ZEROSPAM\Framework\SDK\Utils\Contracts\Enums;

use MabeEnum\Enum;
use ZEROSPAM\Framework\SDK\Utils\Str;

/**
 * Trait EnumToStringLowerTrait
 *
 * Common code for Enum Insensitive implementations
 *
 * @package ZEROSPAM\Framework\SDK\Utils\Contracts\Enums
 */
trait EnumToStringLowerTrait
{
    /**
     * @return string
     */
    function __toString(): string
    {
        return Str::lower(parent::__toString());
    }

    /**
     * Retrieve enumeration instance by name, case insensitive
     *
     * @param string $name
     *
     * @return Enum
     */
    public static function byNameInsensitive(string $name)
    {
        return self::byName(Str::upper($name));
    }

    /**
     * Retrieve enumeration instance by its value, case insensitive
     *
     * @param $value
     *
     * @return Enum
     */
    public static function byValueInsensitive(string $value)
    {
        return self::byValue(Str::lower($value));
    }

    /**
     * String representing the different values of enumeration
     *
     * @param string $sep
     *
     * @return string
     */
    public static function getDisplayableValues($sep = ', '): string
    {
        return implode($sep, self::getEnumerators());
    }

    /**
     * Return an array of enum matching all the given names
     *
     * @param string[] $names
     *
     * @return self[]
     */
    public static function getEnumsByNameInsensitive(array $names): array
    {
        return array_map(
            function (string $name) {
                return self::byNameInsensitive($name);
            }, $names);
    }
}