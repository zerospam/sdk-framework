<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-09-26
 * Time: 13:08
 */

namespace ZEROSPAM\Framework\SDK\Utils\Data;

use ZEROSPAM\Framework\SDK\Request\Api\HasNullableFields;
use ZEROSPAM\Framework\SDK\Request\Api\WithNullableFields;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Utils\Contracts\DataObject;
use ZEROSPAM\Framework\SDK\Utils\Reflection\ReflectionUtil;

/**
 * Class ArrayableData
 *
 * Data to be used in Request. Will be transform into an array
 * By default use all the available properties to do so.
 *
 * @package ZEROSPAM\Framework\SDK\Utils\Data
 */
abstract class ArrayableData implements DataObject, WithNullableFields
{
    use HasNullableFields;

    /** @var string[] */
    protected $renamed = [];

    /**
     * Create the data from the response
     *
     * @param IResponse $response
     *
     * @return ArrayableData
     */
    public static function fromResponse(IResponse $response): self
    {
        $instance = new static();
        $response->populateDataObject($instance);

        return $instance;
    }

    /**
     * Properties to not serialize into array
     *
     * @return array
     */
    protected static function blacklist(): array
    {
        static $blacklist = ['renamed', 'nullableChanged'];

        return $blacklist;
    }

    /**
     * Return the object as Array.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        $data = ReflectionUtil::objToSnakeArray($this, static::blacklist());
        foreach ($this->renamed as $name => $newName) {
            if (!array_key_exists($name, $data)) {
                continue;
            }
            $data[$newName] = $data[$name];
            unset($data[$name]);
        }

        return $data;
    }
}
