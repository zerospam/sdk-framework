<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:55.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Argument;

use GuzzleHttp\RequestOptions;
use ZEROSPAM\Framework\SDK\Test\Base\Argument\IncludeStackableArg;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class StackableArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function add_argument_stack()
    {
        $key     = (new IncludeStackableArg('t'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new IncludeStackableArg('test'))
                ->addArgument(new IncludeStackableArg('superTest'));

        $options = $request->requestOptions();

        $this->assertArrayHasKey($key, $options[RequestOptions::QUERY]);
        $this->assertArraySubset(['test', 'superTest'], $options[RequestOptions::QUERY][$key]);
    }

    /**
     * @test
     */
    public function remove_argument_stack()
    {
        $key     = (new IncludeStackableArg('t'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new IncludeStackableArg('test'))
                ->addArgument(new IncludeStackableArg('superTest'))
                ->addArgument(new IncludeStackableArg('foo'))
                ->removeArgument(new IncludeStackableArg('superTest'));

        $options = $request->requestOptions();

        $this->assertArraySubset(['test', 'foo'], $options[RequestOptions::QUERY][$key]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function remove_not_present_arg()
    {
        $request = new TestRequest();
        $request->removeArgument(new IncludeStackableArg('superTest'));
    }

    /**
     * @test
     */
    public function remove_all_argument_stack()
    {
        $key     = (new IncludeStackableArg('t'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new IncludeStackableArg('test'))
                ->addArgument(new IncludeStackableArg('superTest'))
                ->addArgument(new IncludeStackableArg('foo'))
                ->removeArgument(new IncludeStackableArg('superTest'))
                ->removeArgument(new IncludeStackableArg('foo'))
                ->removeArgument(new IncludeStackableArg('test'));

        $options = $request->requestOptions();

        $this->assertArrayNotHasKey($key, $options[RequestOptions::QUERY]);
    }

    /**
     *
     */
    public function testStackableInUrl(): void
    {
        $client = $this->preSuccess(['test' => 'data']);

        $request = new TestRequest();
        $request->addArgument(new IncludeStackableArg('test'));
        $request->addArgument(new IncludeStackableArg('test2'));
        $client->getOAuthTestClient()
               ->processRequest($request);
        $this->validateQuery($client, 'include[0]=test', 'include[1]=test2');
    }
}
