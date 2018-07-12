<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-07-12
 * Time: 09:43
 */

namespace ZEROSPAM\Framework\SDK\Request\Api;

trait HasNullableFields
{

    /**
     * @var array
     */
    private $nullableChanged = [];

    /**
     * Is the given field nullable
     *
     * @param $field
     *
     * @return bool
     */
    public function isNullable($field)
    {
        return isset($this->nullableChanged[$field]);
    }

    /**
     * Check if the given field is nullable and if it should be included in the request
     *
     * @param $field
     *
     * @return bool
     * @internal param $value
     *
     */
    public function isValueChanged($field)
    {
        if (!$this->IsNullable($field)) {
            return false;
        }

        return isset($this->nullableChanged[$field]);
    }

    /**
     * Trigger the fact the nullable changed
     *
     * @param null $field
     */
    protected function nullableChanged($field = null)
    {
        if (!$field) {
            $function = debug_backtrace()[1]['function'];
            $field    = lcfirst(substr($function, 3));
        }

        $this->nullableChanged[$field] = true;
    }
}
