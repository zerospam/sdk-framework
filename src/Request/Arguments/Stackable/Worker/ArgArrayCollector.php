<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 25/09/18
 * Time: 9:23 AM
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\Worker;


use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\IStackableArgument;
use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\ISubKeyedStackableArgument;

class ArgArrayCollector extends ArgCollector
{
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
}