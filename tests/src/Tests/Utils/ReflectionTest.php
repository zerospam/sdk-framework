<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:24.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils;

use Carbon\Carbon;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Response\Data\TestDataObject;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Response\TestResponse;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;
use ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj\BasicEnum;
use ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj\BasicObj;
use ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj\BasicObjArrayEnum;
use ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj\BasicObjEnum;
use ZEROSPAM\Framework\SDK\Utils\Reflection\ReflectionUtil;

class ReflectionTest extends TestCase
{
    /**
     * @test
     */
    public function reflection_class_to_array()
    {
        $test = new BasicObj('bar');

        $array = ReflectionUtil::objToSnakeArray($test);
        $this->assertArrayHasKey('foo', $array);
        $this->assertEquals('bar', $array['foo']);
    }

    /**
     * @test
     */
    public function reflection_class_to_array_with_enum()
    {
        $test = new BasicObjEnum(BasicEnum::TEST());
        $array = ReflectionUtil::objToSnakeArray($test);
        $this->assertArrayHasKey('enum',$array);
        $this->assertEquals('test', $array['enum']);
    }

    /**
     * @test
     */
    public function reflection_class_to_array_with_array_enum()
    {
        $test  = new BasicObjArrayEnum([BasicEnum::TEST()]);
        $array = ReflectionUtil::objToSnakeArray($test);
        $this->assertArrayHasKey('enum_array',$array);
        $this->assertEquals(['test'], $array['enum_array']);
    }

    public function testDataPopulateFromResponse(): void
    {
        $date     = 'Wed, 26 Sep 2018 15:43:11 GMT';
        $response = new TestResponse(['test_date' => $date, 'test' => 'blah']);
        $dataObj  = \Mockery::mock(new TestDataObject())
                            ->shouldReceive('setTestDate')
                            ->with(Carbon::class)
                            ->andReturnSelf()
                            ->once()
                            ->getMock();

        ReflectionUtil::populateResponseData($response, $dataObj);

        $dataObj->shouldNotHaveReceived('setTest');
    }
}
