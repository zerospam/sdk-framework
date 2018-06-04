<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:35 PM.
 */

namespace ZEROSPAM\Framework\SDK\Response\Api;

/**
 * Interface IResponse.
 *
 * Parsed Response of the server
 */
interface IResponse
{
    /**
     * Data contained in the response.
     *
     * @return array
     */
    public function data(): array;

    /**
     * Get a specific field.
     *
     * @param string $field
     *
     * @return mixed|null
     */
    public function get($field);

    /**
     * Return value in response array of the response.
     *
     * @param $key
     *
     * @return mixed
     */
    public function getRawValue($key);
}
