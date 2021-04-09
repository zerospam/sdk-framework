<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:55.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Argument;

use ZEROSPAM\Framework\SDK\Test\Base\Argument\SearchKeyedArg;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Request\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class SubKeyStackableArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function add_argument_stack()
    {
        $request = new TestRequest();
        $request->addArgument(new SearchKeyedArg('val', 'test'))
                ->addArgument(new SearchKeyedArg('item', 'superTest'));


        $uri = $request->toUri();

        $this->assertEquals('search%5Bval%5D=test&search%5Bitem%5D=superTest', $uri->getQuery());
    }

    /**
     */
    public function testTwiceSameArgSubKeyFail()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new TestRequest();
        $request->addArgument(new SearchKeyedArg('val', 'test'))
                ->addArgument(new SearchKeyedArg('val', 'superTest'));
    }

    /**
     * @test
     */
    public function remove_argument_stack()
    {
        $key     = (new SearchKeyedArg('val', 't'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new SearchKeyedArg('val', 'test'))
                ->addArgument(new SearchKeyedArg('item', 'superTest'))
                ->addArgument(new SearchKeyedArg('item2', 'foo'))
                ->removeArgument(new SearchKeyedArg('item', 'superTest'));


        $uri = $request->toUri();

        $this->assertEquals('search%5Bval%5D=test&search%5Bitem2%5D=foo', $uri->getQuery());
    }

    /**
     * @test
     */
    public function remove_not_present_arg()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new TestRequest();
        $request->removeArgument(new SearchKeyedArg('val', 'superTest'));
    }

    /**
     * @test
     */
    public function remove_all_argument_stack()
    {
        $key     = (new SearchKeyedArg('val', 't'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new SearchKeyedArg('val', 'test'))
                ->addArgument(new SearchKeyedArg('val2', 'superTest'))
                ->addArgument(new SearchKeyedArg('val3', 'foo'))
                ->removeArgument(new SearchKeyedArg('val2', 'superTest'))
                ->removeArgument(new SearchKeyedArg('val3', 'foo'))
                ->removeArgument(new SearchKeyedArg('val', 'test'));

        $uri = $request->toUri();

        $this->assertEmpty($uri->getQuery());
    }

    /**
     *
     */
    public function testStackableInUrl(): void
    {
        $client = $this->preSuccess(['test' => 'data']);

        $request = new TestRequest();
        $request->addArgument(new SearchKeyedArg('val', 'test'));
        $request->addArgument(new SearchKeyedArg('val2', 'test2'));
        $client->getOAuthTestClient()
               ->processRequest($request);
        $this->validateQuery($client, 'search[val]=test', 'search[val2]=test2');
    }
}
