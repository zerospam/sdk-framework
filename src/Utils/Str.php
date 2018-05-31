<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:10 PM
 */

namespace ZEROSPAM\Framework\SDK\Utils;



final class Str
{


    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of camel-cased words.
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     * Str constructor.
     */
    private function __construct()
    {
    }


    /**
     * Convert the given string to lower-case.
     *
     * @param  string $value
     *
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }


    /**
     * Convert the given string to upper-case.
     *
     * @param  string $value
     *
     * @return string
     */
    public static function upper($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param  string $delimiter
     *
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $key = $value;

        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return static::$snakeCache[$key][$delimiter] = $value;
    }


    /**
     * Convert a value to camel case.
     *
     * @param  string $value
     *
     * @return string
     */
    public static function camel($value)
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }

        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }


    /**
     * Convert a value to studly caps case.
     *
     * @param  string $value
     *
     * @return string
     */
    public static function studly($value)
    {
        $value = ucwords(str_replace([
            '-',
            '_',
        ], ' ', $value));

        return str_replace(' ', '', $value);
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     *
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     *
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     *
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string contains all given substring.
     *
     * @param  string $haystack
     * @param  array  $needles
     *
     * @return bool
     */
    public static function containsAll($haystack, array $needles)
    {
        foreach ($needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Replace the first occurrence of needle in the haystack
     *
     * @param string $haystack
     * @param string $needle
     * @param string $replace
     *
     * @return string
     */
    public static function replaceFirst($haystack, $needle, $replace)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }


        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }
}