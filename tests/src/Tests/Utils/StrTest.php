<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:30.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils;

use ZEROSPAM\Framework\SDK\Test\Base\TestCase;
use ZEROSPAM\Framework\SDK\Utils\Str;

class StrTest extends TestCase
{
    /**
     * @test
     */
    public function camelCase()
    {
        $this->assertEquals('camelCase', Str::camel('camel_case'));
    }

    /**
     * @test
     */
    public function camelCase_cache()
    {
        $this->assertEquals('camelCase', Str::camel('camel_case'));
    }

    /**
     * @test
     */
    public function studly()
    {
        $this->assertEquals('StudlyString', Str::studly('studly_string'));
    }

    /**
     * @test
     */
    public function snake()
    {
        $this->assertEquals('snake_string', Str::snake('SnakeString'));
    }

    /**
     * @test
     */
    public function snake_cache()
    {
        $this->assertEquals('snake_string', Str::snake('SnakeString'));
    }

    /**
     * @test
     */
    public function upper()
    {
        $this->assertEquals('NICE', Str::upper('nice'));
    }

    /**
     * @test
     */
    public function lower()
    {
        $this->assertEquals('nice', Str::lower('NICE'));
    }

    /**
     * @test
     */
    public function starts_with()
    {
        $this->assertTrue(Str::startsWith('superTest', 'super'));
    }

    /**
     * @test
     */
    public function starts_with_failed()
    {
        $this->assertFalse(Str::startsWith('superTest', 'Test'));
    }

    /**
     * @test
     */
    public function ends_with()
    {
        $this->assertTrue(Str::endsWith('superTest', 'Test'));
    }

    /**
     * @test
     */
    public function ends_with_failed()
    {
        $this->assertFalse(Str::endsWith('superTest', 'super'));
    }

    /**
     * @test
     */
    public function contains_success()
    {
        $this->assertTrue(Str::contains('superTest', 'super'));
    }

    /**
     * @test
     */
    public function contains_failed()
    {
        $this->assertFalse(Str::contains('superTest', 'nope'));
    }

    /**
     * @test
     */
    public function contains_all_success()
    {
        $this->assertTrue(Str::containsAll('superTest', ['Test', 'super']));
    }

    /**
     * @test
     */
    public function contains_all_failed()
    {
        $this->assertFalse(Str::containsAll('superTest', ['Test', 'nope']));
    }

    /**
     * @test
     */
    public function replace_first_occurence_success()
    {
        $this->assertSame('super_test', Str::replaceFirst('super_nope', 'nope', 'test'));
    }

    /**
     * @test
     */
    public function replace_first_occurence_no_replace()
    {
        $this->assertSame('super_nope', Str::replaceFirst('super_nope', 'test', 'test'));
    }
}
