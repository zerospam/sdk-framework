<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-07-16
 * Time: 09:55
 */

namespace ZEROSPAM\Framework\SDK\Response\Api;

use ZEROSPAM\Framework\SDK\Exception\Response\NoActionEmptyResponseException;

/**
 * Class EmptyResponse
 *
 * Response to represent the code 202
 *
 * @package ZEROSPAM\Framework\SDK\Response\Api
 */
final class EmptyResponse implements IResponse
{

    public function __isset($name)
    {
        return false;
    }

    /**
     * @param $name
     *
     * @throws NoActionEmptyResponseException
     */
    public function __get($name)
    {
        throw new NoActionEmptyResponseException('Empty response has no data');
    }

    /**
     * @param $name
     * @param $value
     *
     * @throws NoActionEmptyResponseException
     */
    public function __set($name, $value)
    {
        throw new NoActionEmptyResponseException('Empty response has no data');
    }


    /**
     * Data contained in the response.
     *
     * @return array
     */
    public function data(): array
    {
        return [];
    }

    /**
     * Get a specific field.
     *
     * @param string $field
     *
     * @return mixed|null
     * @throws NoActionEmptyResponseException
     */
    public function get($field)
    {
        throw new NoActionEmptyResponseException('Empty response has no data');
    }

    /**
     * Return value in response array of the response.
     *
     * @param $key
     *
     * @return mixed
     * @throws NoActionEmptyResponseException
     */
    public function getRawValue($key)
    {
        throw new NoActionEmptyResponseException('Empty response has no data');
    }
}
