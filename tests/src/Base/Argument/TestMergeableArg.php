<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:56.
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Argument;

use ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable\IMergeableArgument;

class TestMergeableArg implements IMergeableArgument
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
        $this->obj = $obj;
    }

    /**
     * Key for the argument.
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'mergeArg';
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

    /**
     * Return a primitive value for this object.
     *
     * @return int|float|string|float
     */
    public function toPrimitive()
    {
        return $this->obj;
    }
}
