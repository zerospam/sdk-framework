<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-11
 * Time: 11:43
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Stackable;

interface ISubKeyedStackableArgument extends IStackableArgument
{
    /**
     * Possible Subkey of the argument
     *
     * @return string
     */
    public function getSubKey(): string;
}
