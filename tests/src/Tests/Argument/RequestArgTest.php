<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 14:23
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Argument;

use PHPUnit\Framework\TestCase;
use ZEROSPAM\Framework\SDK\Request\Arguments\RequestArg;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;

class RequestArgTest extends TestCase
{

    /**
     * @test
     * @expectedException \InvalidArgumentException
     **/
    public function argument_failure_not_string()
    {
        new RequestArg(new \stdClass(), 'test');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     **/
    public function argument_failure_null()
    {
        new RequestArg(null, 'test');
    }

    /**
     * @test
     */
    public function argument_test_boolean_true()
    {
        $test = new RequestArg('test', true);

        $this->assertSame(1, $test->toPrimitive());
    }


    /**
     * @test
     */
    public function argument_test_boolean_false()
    {
        $test = new RequestArg('test', false);

        $this->assertSame(0, $test->toPrimitive());
    }

    /**
     * @test
     **/
    public function add_remove_args()
    {
        $request = new TestRequest();
        $arg     = new RequestArg('test', 'test');
        $request->addArgument($arg)
                ->removeArgument($arg);

        $this->assertArrayNotHasKey($arg->getKey(), $request->getArguments());
    }
}
