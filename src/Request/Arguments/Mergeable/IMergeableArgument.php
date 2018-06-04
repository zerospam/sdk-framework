<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:41 PM.
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable;

use ZEROSPAM\Framework\SDK\Request\Arguments\IArgument;

/**
 * Interface IMergeableArgument.
 *
 * Argument that can be used multiple time in the same query.
 */
interface IMergeableArgument extends IArgument
{
    /**
     * Character used to glue the same args together.
     *
     * @return string
     */
    public function glue();
}
