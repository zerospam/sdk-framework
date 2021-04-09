<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:55.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Argument;

use ZEROSPAM\Framework\SDK\Test\Base\Argument\TestMergeableArg;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Request\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class MergeableArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function add_argument_merge()
    {
        $request = new TestRequest();
        $request->addArgument(new TestMergeableArg('test'))
                ->addArgument(new TestMergeableArg('superTest'));

        $uri = $request->toUri();

        $this->assertStringContainsString('mergeArg=test%3BsuperTest', $uri->getQuery());
    }

    /**
     * @test
     */
    public function remove_argument_merge()
    {
        $request = new TestRequest();
        $request->addArgument(new TestMergeableArg('test'))
                ->addArgument(new TestMergeableArg('superTest'))
                ->addArgument(new TestMergeableArg('foo'))
                ->removeArgument(new TestMergeableArg('superTest'));


        $uri = $request->toUri();

        $this->assertStringContainsString('mergeArg=test%3Bfoo', $uri->getQuery());
    }

    /**
     * @test
     */
    public function remove_not_present_arg()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new TestRequest();
        $request->removeArgument(new TestMergeableArg('superTest'));
    }

    /**
     *
     */
    public function testMergeableInUrl(): void
    {
        $client = $this->preSuccess(['test' => 'data']);

        $request = new TestRequest();
        $request->addArgument(new TestMergeableArg('test'))
                ->addArgument(new TestMergeableArg('superTest'));
        $client->getOAuthTestClient()
               ->processRequest($request);
        $this->validateQuery($client, 'mergeArg=test;superTest');
    }
}
