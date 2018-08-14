<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 14/08/18
 * Time: 9:10 AM
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Enums;


use ZEROSPAM\Framework\SDK\Test\Base\TestCase;
use ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj\BasicEnum;

class EnumInsensitiveTest extends TestCase
{
    /**
     * @test
     */
    public function byNameInsensitiveSuccess()
    {
        $this->assertTrue(BasicEnum::byNameInsensitive('test')->is(BasicEnum::TEST()));
    }

    /**
     * @test
     */
    public function byValueInsensitiveSuccess()
    {
        $this->assertTrue(BasicEnum::byValueInsensitive('TEST')->is(BasicEnum::TEST()));
    }

    /**
     * @test
     */
    public function getDisplayableValueSuccess()
    {
        $this->assertEquals('test, other', BasicEnum::getDisplayableValues());
    }

    /**
     * @test
     */
    public function getEnumsByNameInsensitive()
    {
        $enums = BasicEnum::getEnumsByNameInsensitive(['test', 'other']);
        $this->assertArraySubset(
            [
                BasicEnum::TEST(),
                BasicEnum::OTHER()
            ],
            $enums
        );
    }
}