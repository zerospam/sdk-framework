<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-07-12
 * Time: 09:44
 */

namespace ZEROSPAM\Framework\SDK\Request\Api;

interface WithNullableFields
{

    /**
     * Is the given field nullable
     *
     * @param $field
     *
     * @return bool
     */
    public function isNullable($field);

    /**
     * Check if the given field is nullable and if it should be included in the request
     *
     * @param $field
     *
     * @return bool
     *
     */
    public function isValueChanged($field);
}
