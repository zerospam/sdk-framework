<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-18
 * Time: 11:38
 */

namespace ZEROSPAM\Framework\SDK\Response\Api\Collection;

use ZEROSPAM\Framework\SDK\Response\Api\BaseResponse;
use ZEROSPAM\Framework\SDK\Response\Api\Collection\Iterator\ImmutableTransformerIterator;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;

/**
 * Class CollectionResponse
 *
 * Represent a response that contains more Responses
 *
 * @package ZEROSPAM\Framework\SDK\Response\Api\Collection
 */
abstract class CollectionResponse extends BaseResponse implements \IteratorAggregate, \ArrayAccess
{

    /**
     * @var CollectionMetaData
     */
    private $metaData;

    /**
     * CollectionResponse constructor.
     *
     * @param CollectionMetaData $metaData
     * @param string[]           $data contains the json deserialized into an array of string (or matrix of string)
     */
    public function __construct(CollectionMetaData $metaData, array $data)
    {
        $this->metaData = $metaData;
        parent::__construct($data);
    }

    /**
     * Transform the basic data (string[]) into a response (IResponse)
     *
     * @param array $data
     *
     * @return IResponse
     */
    abstract protected static function dataToResponse(array $data);


    /**
     * Meta data of the collection (pagination mostly)
     *
     * @return CollectionMetaData
     */
    public function getMetaData(): CollectionMetaData
    {
        return $this->metaData;
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @since 5.0.0
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ImmutableTransformerIterator(
            function (array $data) {
                return static::dataToResponse($data);
            },
            $this->data
        );
    }


    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->getIterator()->offsetExists($offset);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->getIterator()->offsetGet($offset);
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->getIterator()->offsetSet($offset, $value);
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->getIterator()->offsetUnset($offset);
    }
}
