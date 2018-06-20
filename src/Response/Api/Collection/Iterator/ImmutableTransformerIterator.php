<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-20
 * Time: 14:33
 */

namespace ZEROSPAM\Framework\SDK\Response\Api\Collection\Iterator;

class ImmutableTransformerIterator extends \ArrayIterator
{

    /**
     * @var \Closure
     */
    private $objBuilder;

    public function __construct(\Closure $objBuilder, array $array = [], int $flags = 0)
    {
        parent::__construct($array, $flags);
        $this->objBuilder = $objBuilder;
    }

    public function offsetGet($index)
    {
        $builder = $this->objBuilder;

        return $builder(parent::offsetGet($index));
    }

    public function current()
    {
        $builder = $this->objBuilder;

        return $builder(parent::current());
    }


    public function append($value)
    {
        throw  new \InvalidArgumentException("Can't modify collection");
    }


    public function offsetSet($index, $newval)
    {
        throw  new \InvalidArgumentException("Can't modify collection");
    }

    public function offsetUnset($index)
    {
        throw  new \InvalidArgumentException("Can't modify collection");
    }
}
