<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 25/09/18
 * Time: 9:40 AM
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Stackable;

/**
 * Interface IStackableArrayArgument
 *
 * Contract for arguments using Freshbooks specific format: ex. ?search[statusids][]=1&search[statusids][]=2
 *
 * @package ZEROSPAM\Framework\SDK\Request\Arguments\Stackable
 */
interface IStackableArrayArgument extends ISubKeyedStackableArgument
{
}