<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:35 PM.
 */

namespace ZEROSPAM\Framework\SDK\Response\Api;

use ZEROSPAM\Framework\SDK\Utils\Contracts\DataObject;

/**
 * Interface IResponse
 *
 * Parsed Response of the server
 *
 * @package ZEROSPAM\Framework\SDK\Response\Api
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

    /**
     * Populate the data object with the data present in the response
     *
     * @param DataObject $dataObject
     */
    public function populateDataObject(DataObject &$dataObject): void;
}
