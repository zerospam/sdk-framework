<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:25 PM.
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments;

use ZEROSPAM\Framework\SDK\Utils\Contracts\PrimalValued;

/**
 * Class RequestArg.
 *
 * Basic query argument
 */
class RequestArg implements IArgument
{
    /**
     * @var string
     */
    private $key;
    /** @var string */
    private $value;

    /**
     * RequestArg constructor.
     *
     * @param string $key
     * @param string $value
     */
    public function __construct($key, $value)
    {
        if (is_null($key)) {
            throw new \InvalidArgumentException("Key can't be null");
        }
        if (!is_string($key)) {
            throw new \InvalidArgumentException('The key need to be a string');
        }
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Key for the argument.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public function toPrimitive()
    {
        $value = $this->value;
        if ($value instanceof PrimalValued) {
            $value = $value->toPrimitive();
        }

        if (is_bool($value)) {
            return (int) $value;
        }

        if (is_float($value)) {
            return str_replace(',', '.', $value);
        }

        return $value;
    }
}
