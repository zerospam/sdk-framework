<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:24.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils;

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
        $this->assertArraySubset(['foo' => 'bar'], ReflectionUtil::objToSnakeArray($test));
    }

    /**
     * @test
     */
    public function reflection_class_to_array_with_enum()
    {
        $test = new BasicObjEnum(BasicEnum::TEST());
        $this->assertArraySubset(['enum' => 'test'], ReflectionUtil::objToSnakeArray($test));
    }

    /**
     * @test
     */
    public function reflection_class_to_array_with_array_enum()
    {
        $test = new BasicObjArrayEnum([BasicEnum::TEST()]);
        $array = ReflectionUtil::objToSnakeArray($test);
        $this->assertArraySubset(['enum_array' => ['test']], $array);
    }
}
