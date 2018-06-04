<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:52 PM.
 */

namespace ZEROSPAM\Framework\SDK\Utils\Reflection;

use Carbon\Carbon;
use ZEROSPAM\Framework\SDK\Utils\Contracts\Arrayable;
use ZEROSPAM\Framework\SDK\Utils\Contracts\PrimalValued;
use ZEROSPAM\Framework\SDK\Utils\Str;

/**
 * Class ReflectionUtil
 *
 * Helper for gathering all the properties of an object
 *
 * @package ZEROSPAM\Framework\SDK\Utils\Reflection
 */
final class ReflectionUtil
{
    private function __construct()
    {
    }

    /**
     * Take an object and return an array using all it's properties.
     *
     * Don't set the null one
     *
     * @param       $object    *
     * @param array $blackList array containing name of properties to not serialize
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    public static function objToSnakeArray($object, $blackList = [])
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Not an object');
        }

        return array_reduce(
            self::getAllProperties($object, $blackList),
            function (
                array $result,
                \ReflectionProperty $property
            ) use (
                $object
            ) {
                $property->setAccessible(true);

                $value = $property->getValue($object);
                $value = self::transformValue($value);

                $field = $property->getName();

                $result[Str::snake($field)] = $value;

                $property->setAccessible(false);

                return $result;
            },
            []
        );
    }

    /**
     * Transform the value.
     *
     * @param mixed $value
     *
     * @return array|float|int|null|string
     */
    private static function transformValue($value)
    {
        if (is_null($value)) {
            return $value;
        }

        if ($value instanceof PrimalValued) {
            $value = $value->toPrimitive();
        } elseif ($value instanceof Arrayable) {
            $value = $value->toArray();
            if (empty($value)) {
                $value = null;
            }
        } elseif (is_array($value)) {
            $value = array_map(function ($arrValue) {
                return self::transformValue($arrValue);
            }, $value);
        } elseif ($value instanceof \DateTimeZone) {
            $value = $value->getName();
        } elseif ($value instanceof Carbon) {
            $value = $value->toRfc3339String();
        }

        return $value;
    }

    /**
     * Get all the properties not blacklisted.
     *
     * @param       $class
     * @param array $blacklist
     *
     * @throws \ReflectionException
     *
     * @return array|\ReflectionProperty[]
     */
    public static function getAllProperties($class, $blacklist = [])
    {
        $classReflection = new \ReflectionClass($class);

        $properties = $classReflection->getProperties();

        if ($parentClass = $classReflection->getParentClass()) {
            $parentProps = self::getAllProperties($parentClass->getName(), $blacklist);
            if (!empty($parentProps)) {
                $properties = array_merge($parentProps, $properties);
            }
        }
        if (empty($blacklist)) {
            return $properties;
        }

        return array_filter($properties, function (\ReflectionProperty $property) use ($blacklist) {
            return !in_array($property->name, $blacklist);
        });
    }
}
