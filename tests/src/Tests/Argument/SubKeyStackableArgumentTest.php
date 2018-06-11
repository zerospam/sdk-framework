<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:55.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Argument;

use GuzzleHttp\RequestOptions;
use ZEROSPAM\Framework\SDK\Test\Base\Argument\SearchKeyedArg;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class SubKeyStackableArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function add_argument_stack()
    {
        $key     = (new SearchKeyedArg('val', 't'))->getKey();
        $request = new TestRequest();
        $request->addArgument(new SearchKeyedArg('val', 'test'))
                ->addArgument(new SearchKeyedArg('item', 'superTest'));

        $options = $request->requestOptions();

        $this->assertArrayHasKey($key, $options[RequestOptions::QUERY]);
        $this->assertArraySubset(['val' => 'test', 'item' => 'superTest'], $options[RequestOptions::QUERY][$key]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testTwiceSameArgSubKeyFail()
    {
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

        $options = $request->requestOptions();

        $this->assertArraySubset(['val' => 'test', 'item2' => 'foo'], $options[RequestOptions::QUERY][$key]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function remove_not_present_arg()
    {
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
        $request->addArgument(new SearchKeyedArg('val', 'test'));
        $request->addArgument(new SearchKeyedArg('val2', 'test2'));
        $client->getOAuthTestClient()
               ->processRequest($request);
        $this->validateQuery($client, 'search[val]=test', 'search[val2]=test2');
    }
}
