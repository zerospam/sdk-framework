<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:35 PM
 */

namespace ZEROSPAM\Framework\SDK\Response;

interface IResponse
{
    /**
     * Data contained in the response
     *
     * @return array
     */
    public function data(): array;

    /**
     * Get a specific field
     *
     * @param string $field
     *
     * @return mixed|null
     */
    public function get($field);
}
