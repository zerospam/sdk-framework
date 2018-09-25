<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:56.
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Argument;

use ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable\IMergeableArgument;
use ZEROSPAM\Framework\SDK\Request\Arguments\RequestArg;

/**
 * Class TestMergeableArg
 *
 * Fake Mergeable argument for testing
 *
 * @package ZEROSPAM\Framework\SDK\Test\Base\Argument
 */
class TestMergeableArg extends RequestArg implements IMergeableArgument
{
    /**
     * @var string
     */
    private $obj;

    /**
     * TestMergeableArg constructor.
     *
     * @param string $obj
     */
    public function __construct(string $obj)
    {
        parent::__construct('mergeArg', $obj);
    }

    /**
     * Character used to glue the same args together.
     *
     * @return string
     */
    public static function glue()
    {
        return ';';
    }
}
