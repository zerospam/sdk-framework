<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:08 PM
 */

namespace ZEROSPAM\Framework\SDK\Response\Api;

use Carbon\Carbon;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Utils\Str;

abstract class BaseResponse implements IResponse
{

    /**
     * @var object[]
     */
    private $objReplacementCache = [];
    /**
     * @var array
     */
    protected $data;

    /**
     * Dates to be transTyped from string to Carbon
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
     * Id in the response
     *
     * @return int
     */
    public function id(): int
    {
        return $this->data['id'];
    }

    /**
     * Data contained in the response
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data['response'];
    }


    /**
     * Return value in response array of the response
     *
     * @param $key
     *
     * @return mixed
     */
    protected function getValue($key)
    {
        if (!array_key_exists($key, $this->data())) {
            throw new \InvalidArgumentException(sprintf('key [%s] is not present in response array.', $key));
        }

        return $this->data()[$key];
    }

    /**
     * Get a specific field
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

            return $this->objReplacementCache[$field] = call_user_func([
                $this,
                $key,
            ]);
        }

        //Same as before but specific for dates
        if (isset($this->dates) && in_array($field, $this->dates)) {
            if (isset($this->objReplacementCache[$field])) {
                return $this->objReplacementCache[$field];
            }

            if (!isset($this->data['response'])) {
                return null;
            }

            if (is_null($this->data['response'][$field])) {
                return null;
            }

            if (!$dateTime
                = Carbon::parse($this->data['response'][$field])
            ) {
                throw new \InvalidArgumentException('Date cannot be parsed');
            }

            return $this->objReplacementCache[$field] = $dateTime;
        }

        if (isset($this->data['response'][$field])) {
            return $this->data['response'][$field];
        }

        if (isset($this->data[$field])) {
            return $this->data[$field];
        }

        return null;
    }


    function __isset($name)
    {
        $key = 'get' . Str::studly($name) . 'Attribute';

        return isset($this->data['response'][$name])
               || isset($this->data[$name])
               || method_exists($this, $key)
               || (isset($this->dates) && in_array($name, $this->dates));
    }


    function __get($name)
    {
        return $this->get($name);
    }
}
