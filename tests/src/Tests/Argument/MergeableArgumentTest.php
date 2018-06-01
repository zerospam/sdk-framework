<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:55
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Argument;

use GuzzleHttp\RequestOptions;
use ZEROSPAM\Framework\SDK\Test\Base\Argument\TestMergeableArg;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class MergeableArgumentTest extends TestCase
{

    /**
     * @test
     */
    public function add_argument_merge()
    {
        $key     = (new TestMergeableArg('t'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new TestMergeableArg('test'))
                ->addArgument(new TestMergeableArg('superTest'));

        $options = $request->requestOptions();

        $this->assertEquals('test;superTest', $options[RequestOptions::QUERY][$key]);
    }

    /**
     * @test
     */
    public function remove_argument_merge()
    {
        $key     = (new TestMergeableArg('t'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new TestMergeableArg('test'))
                ->addArgument(new TestMergeableArg('superTest'))
                ->addArgument(new TestMergeableArg('foo'))
                ->removeArgument(new TestMergeableArg('superTest'));

        $options = $request->requestOptions();

        $this->assertEquals('test;foo', $options[RequestOptions::QUERY][$key]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function remove_not_present_arg()
    {
        $request = new TestRequest();
        $request->removeArgument(new TestMergeableArg('superTest'));
    }
}
