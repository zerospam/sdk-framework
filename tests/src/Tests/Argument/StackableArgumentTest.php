<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:55.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Argument;

use ZEROSPAM\Framework\SDK\Test\Base\Argument\IncludeStackableArg;
use ZEROSPAM\Framework\SDK\Test\Base\Argument\SearchArgumentArray;
use ZEROSPAM\Framework\SDK\Test\Base\Data\TestRequest;
use ZEROSPAM\Framework\SDK\Test\Base\TestCase;

class StackableArgumentTest extends TestCase
{
    /**
     * @test
     */
    public function add_argument_stack()
    {
        $request = new TestRequest();
        $request->addArgument(new IncludeStackableArg('test'))
                ->addArgument(new IncludeStackableArg('superTest'));


        $uri = $request->toUri();

        $this->assertContains('include%5B%5D=test&include%5B%5D=superTest', $uri->getQuery());
    }

    /**
     * @test
     */
    public function remove_argument_stack()
    {
        $request = new TestRequest();
        $request->addArgument(new IncludeStackableArg('test'))
                ->addArgument(new IncludeStackableArg('superTest'))
                ->addArgument(new IncludeStackableArg('foo'))
                ->removeArgument(new IncludeStackableArg('superTest'));


        $uri = $request->toUri();

        $this->assertContains('include%5B%5D=test&include%5B%5D=foo', $uri->getQuery());
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
        $request = new TestRequest();
        $request->addArgument(new IncludeStackableArg('test'))
                ->addArgument(new IncludeStackableArg('superTest'))
                ->addArgument(new IncludeStackableArg('foo'))
                ->removeArgument(new IncludeStackableArg('superTest'))
                ->removeArgument(new IncludeStackableArg('foo'))
                ->removeArgument(new IncludeStackableArg('test'));

        $uri = $request->toUri();

        $this->assertNotContains('include%5B%5D=test&include%5B%5D=superTest&include%5B%5D=foo', $uri->getQuery());
        $this->assertEmpty($uri->getQuery());
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
        $this->validateQuery($client, 'include[]=test', 'include[]=test2');
    }

    /**
     *
     */
    public function testArrayStackableInUrl(): void
    {
        $client = $this->preSuccess([]);

        $request = new TestRequest();
        $request->addArgument(new SearchArgumentArray('statusids', [1, 2]));
        $client->getOAuthTestClient()
               ->processRequest($request);
        $this->validateQuery($client, 'search[statusids][]=1', 'search[statusids][]=2');
    }

    /**
     *
     */
    public function removeArrayStackable(): void
    {
        $client = $this->preSuccess([]);

        $request = new TestRequest();
        $request->addArgument(new SearchArgumentArray('statusids', [1]))
                ->removeArgument(new SearchArgumentArray('statusids', [1]));

        $client->getOAuthTestClient()
               ->processRequest($request);

        $uri = $request->toUri();

        $this->assertNotContains('search[statusids][]=1', $uri->getQuery());
    }
}
