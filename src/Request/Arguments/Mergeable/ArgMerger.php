<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:40 PM.
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable;

use ZEROSPAM\Framework\SDK\Utils\Contracts\PrimalValued;

class ArgMerger implements PrimalValued
{
    /**
     * @var IMergeableArgument[]
     */
    private $args = [];

    /**
     * Add the argument.
     *
     * @param IMergeableArgument $argument
     *
     * @return $this
     */
    public function addArgument(IMergeableArgument $argument)
    {
        $this->args[$argument->toPrimitive()] = $argument;

        return $this;
    }

    /**
     * Remove the argument.
     *
     * @param IMergeableArgument $argument
     *
     * @return $this
     */
    public function removeArgument(IMergeableArgument $argument)
    {
        unset($this->args[$argument->toPrimitive()]);

        return $this;
    }

    /**
     * Return a primitive value for this object.
     *
     * @return int|float|string|float
     */
    public function toPrimitive()
    {
        if (empty($this->args)) {
            throw new \InvalidArgumentException("Args shouldn't be empty");
        }

        $values = array_keys($this->args);

        return implode($this->args[$values[0]]->glue(), $values);
    }

    /**
     * Is there arguments to be merged for request parameter.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->args);
    }
}
