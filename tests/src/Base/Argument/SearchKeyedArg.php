<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-11
 * Time: 13:22
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Argument;

use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\SubKeyedStackableRequestArg;

/**
 * Class SearchKeyedArg
 *
 * A search argument
 *
 * @package ZEROSPAM\Framework\SDK\Test\Base\Argument
 */
class SearchKeyedArg extends SubKeyedStackableRequestArg
{


    public function __construct(string $subKey, string $value)
    {
        parent::__construct('search', $subKey, $value);
    }
}
