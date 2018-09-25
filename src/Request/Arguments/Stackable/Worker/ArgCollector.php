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
use ZEROSPAM\Framework\SDK\Utils\Contracts\Arrayable;

class ArgCollector implements Arrayable
{

    /**
     * @var IStackableArgument[][]
     */
    protected $args = [];

    /**
     * @var string
     */
    protected $stackKey;


    /**
     * Get the unique stack key
     *
     * @return string
     */
    protected function stackKey(): string
    {
        if ($this->stackKey) {
            return $this->stackKey;
        }

        return $this->stackKey = uniqid('stack_key');
    }

    /**
     * @param IStackableArgument $argument
     *
     * @return $this|ArgCollector
     */
    public function addArgument(IStackableArgument $argument)
    {
        if ($argument instanceof ISubKeyedStackableArgument) {
            $key = $argument->getSubKey();
            if (isset($this->args[$key])) {
                throw new \InvalidArgumentException("Can't override the subkey: $key");
            }
        } else {
            $key = $this->stackKey();
        }

        $this->args[$key][$argument->getSubKey()] = $argument;

        return $this;
    }

    /**
     * @param IStackableArgument $argument
     *
     * @return $this|ArgCollector
     */
    public function removeArgument(IStackableArgument $argument)
    {
        if ($argument instanceof ISubKeyedStackableArgument) {
            $key = $argument->getSubKey();
        } else {
            $key = $this->stackKey();
        }

        unset($this->args[$key][$argument->getSubKey()]);
        if (empty($this->args[$key])) {
            unset($this->args[$key]);
        }

        return $this;
    }


    public function toArray(): array
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
            $result[$subKey] = current($arg)->toPrimitive();
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
