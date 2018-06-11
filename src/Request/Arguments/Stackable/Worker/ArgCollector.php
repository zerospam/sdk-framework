<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-11
 * Time: 11:55
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\Worker;

use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\IStackableArgument;
use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\ISubKeyedStackableArgument;

class ArgCollector
{

    /**
     * @var IStackableArgument[][]
     */
    private $args = [];

    /**
     * @var string
     */
    private $stackKey;

    /**
     * ArgCollector constructor.
     */
    public function __construct()
    {
        $this->stackKey = uniqid('stack_key');
    }


    /**
     * Add the argument.
     *
     * @param IStackableArgument $argument
     *
     * @return $this
     */
    public function addArgument(IStackableArgument $argument)
    {
        $key = $this->stackKey;
        if ($argument instanceof ISubKeyedStackableArgument) {
            $key = $argument->getSubKey();
        }
        $this->args[$key][$argument->toPrimitive()] = $argument;

        return $this;
    }

    /**
     * Remove the argument.
     *
     * @param IStackableArgument $argument
     *
     * @return $this
     */
    public function removeArgument(IStackableArgument $argument)
    {

        $key = $this->stackKey;
        if ($argument instanceof ISubKeyedStackableArgument) {
            $key = $argument->getSubKey();
        }

        unset($this->args[$key][$argument->toPrimitive()]);
        if (empty($this->args[$key])) {
            unset($this->args[$key]);

            return $this;
        }
    }

    /**
     * Return a primitive value for this object.
     *
     * @return int|float|string|float|array
     */
    public function toPrimitive()
    {
        $result = [];
        if (isset($this->args[$this->stackKey])) {
            $result = array_keys($this->args[$this->stackKey]);

            return $result;
        }

        /**
         * @var string                       $subKey
         * @var ISubKeyedStackableArgument[] $arg
         */
        foreach ($this->args as $subKey => $arg) {
            $result[$subKey] = array_keys($arg);
        }

        return $result;
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
