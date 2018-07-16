<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:08 PM.
 */

namespace ZEROSPAM\Framework\SDK\Response\Api;

use Carbon\Carbon;
use ZEROSPAM\Framework\SDK\Response\Api\Helper\RateLimitedTrait;
use ZEROSPAM\Framework\SDK\Utils\Str;

/**
 * Class BaseResponse
 *
 * Reponse given by the client when processing a request.
 * Take care of providing generator for bindings
 *
 * @package ZEROSPAM\Framework\SDK\Response\Api
 */
abstract class BaseResponse implements IRateLimitedResponse
{
    use RateLimitedTrait;

    /**
     * @var object[]
     */
    private $objReplacementCache = [];
    /**
     * @var array
     */
    protected $data;

    /**
     * Dates to be transTyped from string to Carbon.
     *
     * @var string[]
     */
    protected $dates = [];

    /**
     * UserCreationResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Data contained in the response.
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Return value in response array of the response.
     *
     * @param $key
     *
     * @return mixed
     */
    public function getRawValue($key)
    {
        if (!array_key_exists($key, $this->data())) {
            throw new \InvalidArgumentException(sprintf('key [%s] is not present in response array.', $key));
        }

        return $this->data()[$key];
    }

    /**
     * Get a specific field.
     *
     * @param string $field
     *
     * @return mixed|null
     */
    public function get($field)
    {
        $key = 'get' . Str::studly($field) . 'Attribute';

        //check if attribute transformer exists
        //Run it if exists and cache the result
        if (method_exists($this, $key)) {
            if (isset($this->objReplacementCache[$field])) {
                return $this->objReplacementCache[$field];
            }

            return $this->objReplacementCache[$field] = call_user_func(
                [
                    $this,
                    $key,
                ]
            );
        }

        //Same as before but specific for dates
        if (isset($this->dates) && in_array($field, $this->dates)) {
            if (isset($this->objReplacementCache[$field])) {
                return $this->objReplacementCache[$field];
            }

            if (!isset($this->data[$field])) {
                return;
            }

            if (!$dateTime
                = Carbon::parse($this->data[$field])
            ) {
                throw new \InvalidArgumentException('Date cannot be parsed');
            }

            return $this->objReplacementCache[$field] = $dateTime;
        }

        if (isset($this->data[$field])) {
            return $this->data[$field];
        }
    }

    public function __isset($name)
    {
        $key = 'get' . Str::studly($name) . 'Attribute';

        return isset($this->data[$name])
               || method_exists($this, $key)
               || (isset($this->dates) && in_array($name, $this->dates));
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}
