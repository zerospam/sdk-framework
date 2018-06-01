<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:38
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Request;

use ZEROSPAM\Framework\SDK\Request\Api\BindableRequest;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj\BasicEnum;

class BindableTestRequest extends BindableRequest
{


    private $test;

    /**
     * @param mixed $test
     *
     * @return $this
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }


    /**
     * CAN'T OVERRIDE
     *
     * @param $id
     *
     * @return $this
     */
    public function setNiceId($id)
    {
        $this->addBinding('niceId', $id, false);

        return $this;
    }

    public function setTestId($id)
    {
        $this->addBinding('testId', $id);

        return $this;
    }

    public function setTestEnum(BasicEnum $enum)
    {
        $this->addBinding('testId', $enum);

        return $this;
    }

    /**
     * Base route without binding
     *
     * @return string
     */
    function baseRoute(): string
    {
        return 'test/:testId/nice/:niceId';
    }

    /**
     * Type of request
     *
     * @return RequestType
     */
    public function httpType(): RequestType
    {
        // TODO: Implement httpType() method.
    }

    /**
     * Process the data that is in the response
     *
     * @param array $jsonResponse
     *
     * @return \ZEROSPAM\Framework\SDK\Response\Api\IResponse
     */
    public function processResponse(array $jsonResponse): IResponse
    {
        // TODO: Implement processResponse() method.
    }
}
