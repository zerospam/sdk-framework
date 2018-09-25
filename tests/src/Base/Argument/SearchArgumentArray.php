<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 25/09/18
 * Time: 9:53 AM
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Argument;


use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\SubKeyedArrayStackableRequestArg;

class SearchArgumentArray extends SubKeyedArrayStackableRequestArg
{
    /**
     * SearchArrayArgument constructor.
     *
     * @param string $searchKey
     * @param array  $value
     */
    public function __construct(string $searchKey, array $value)
    {
        parent::__construct('search', $searchKey, $value);
    }
}