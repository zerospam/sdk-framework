<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-08
 * Time: 11:29
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Argument;

use ZEROSPAM\Framework\SDK\Request\Arguments\RequestArg;
use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\IStackableArgument;

/**
 * Class IncludeStackableArg
 *
 * Fake Stackable argument for testing
 *
 * @package ZEROSPAM\Framework\SDK\Test\Base\Argument
 */
class IncludeStackableArg extends RequestArg implements IStackableArgument
{
    public function __construct(string $value)
    {
        parent::__construct('include', $value);
    }
}
