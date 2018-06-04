<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:40.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Request;

use ZEROSPAM\Framework\SDK\Test\Base\Request\BindableMultiTestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\Request\BindableTestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;
use ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj\BasicEnum;

class BindableRequestTest extends TestCase
{
    /**
     * @test
     */
    public function bindable_replace_bindings()
    {
        $bindableRequest = new BindableTestRequest();
        $bindableRequest->setNiceId(4)->setTestId(5);

        $this->assertEquals('test/5/nice/4', $bindableRequest->routeUrl());
    }

    /**
     * @test
     */
    public function bindable_replace_bindings_enum()
    {
        $bindableRequest = new BindableTestRequest();
        $bindableRequest->setNiceId(4)->setTestEnum(BasicEnum::TEST());

        $this->assertEquals('test/test/nice/4', $bindableRequest->routeUrl());
    }

    /**
     * @test
     */
    public function bindable_replace_bindings_multiple()
    {
        $bindableRequest = new BindableMultiTestRequest();
        $bindableRequest->setNiceId(4)->setTestId(5);

        $this->assertEquals('test/5/nice/4/super/4', $bindableRequest->routeUrl());
    }

    /**
     * @test
     */
    public function bindable_test_to_array_without_bindings()
    {
        $bindableRequest = new BindableMultiTestRequest();
        $bindableRequest->setNiceId(4)->setTestId(5)->setTest('foo');

        $toArray = $bindableRequest->toArray();
        $this->assertArrayNotHasKey('bindings', $toArray);
        $this->assertArrayHasKey('test', $toArray);
        $this->assertEquals('foo', $toArray['test']);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function bindable_failure_exception_object()
    {
        $bindableRequest = new BindableMultiTestRequest();
        $bindableRequest->setNiceId(new \stdClass());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function bindable_failure_exception_array()
    {
        $bindableRequest = new BindableMultiTestRequest();
        $bindableRequest->setNiceId([]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function bindable_failure_override()
    {
        $bindableRequest = new BindableMultiTestRequest();
        $bindableRequest->setNiceId(1)->setNiceId(5);
    }
}
