<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:41 PM
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable;


use ZEROSPAM\Framework\SDK\Request\Arguments\IArgument;

interface IMergeableArgument extends IArgument
{

    /**
     * Character used to glue the same args together
     *
     * @return string
     */
    public function glue();
}