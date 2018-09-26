<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 13:51.
 */

namespace ZEROSPAM\Framework\SDK\Test\Base;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use ZEROSPAM\Framework\SDK\Test\Base\Config\TestClient;
use ZEROSPAM\Framework\SDK\Test\Base\Container\Transaction;

/**
 * Base for making the different tests
 *
 * Help you with a TestClient and preparing different kind of request
 *
 * @package ZEROSPAM\Framework\SDK\Test\Base
 */
class TestCase extends \PHPUnit\Framework\TestCase
{


    public function tearDown()
    {
        if ($container = m::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        m::close();
    }

    /**
     * @param MockHandler $handler
     *
     * @see http://docs.guzzlephp.org/en/latest/testing.html
     *
     * @return TestClient
     */
    protected function getTestClient(MockHandler $handler): TestClient
    {
        return new TestClient($handler);
    }

    /**
     * The last transaction done (first if only one done).
     *
     * @param TestClient $conf
     *
     * @return Transaction|null null if no transaction done
     */
    protected function lastTransaction(TestClient $conf): ?Transaction
    {
        $last = count($conf->getContainer()) - 1;
        if ($last < 0) {
            return null;
        }
        $lastTrans = $conf->getContainer()[$last];

        return new Transaction(
            $lastTrans['request'],
            $lastTrans['options'],
            $lastTrans['response'],
            $lastTrans['error']
        );
    }

    /**
     * All the transaction in order.
     *
     * @param TestClient $conf
     *
     * @return Transaction[]
     */
    protected function allTransactions(TestClient $conf): array
    {
        return array_map(
            function ($transaction) {
                return new Transaction(
                    $transaction['request'],
                    $transaction['options'],
                    $transaction['response'],
                    $transaction['error']
                );
            },
            $conf->getContainer()
        );
    }

    /**
     * Run to set a success.
     *
     * @param string|string[] $responseBody
     * @param int             $statusCode
     *
     * @return TestClient
     */
    protected function preSuccess($responseBody, $statusCode = 200): TestClient
    {
        if (is_array($responseBody)) {
            $responseBody = \GuzzleHttp\json_encode($responseBody, true);
        }

        $mockHandler = new MockHandler(
            [
                new Response($statusCode, [], $responseBody),
            ]
        );

        $config = $this->getTestClient($mockHandler);

        return $config;
    }

    /**
     * Validate that the request did contain the wanted arguments.
     *
     * @param TestClient      $config
     * @param string|string[] $requestBody
     */
    protected function validateRequest(TestClient $config, $requestBody): void
    {
        $trans  = $this->lastTransaction($config);
        $decode = \GuzzleHttp\json_decode($trans->request()->getBody()->getContents(), true);
        if (is_array($requestBody)) {
            $sent = $requestBody;
        } else {
            $sent = \GuzzleHttp\json_decode($requestBody, true);
        }

        $this->assertArraySubset($sent, $decode, false, 'Request contains all we want');
    }

    /**
     * Prepare for a failure.
     *
     * @param string|string[] $responseBody
     * @param int             $statusCode Set the status code of the response
     *
     * @return TestClient
     */
    protected function preFailure($responseBody, $statusCode = 422): TestClient
    {
        if ($statusCode < 400) {
            throw new \InvalidArgumentException('The status code to prepare for failure need to be >=400');
        }

        if (is_array($responseBody)) {
            $responseBody = \GuzzleHttp\json_encode($responseBody, true);
        }

        $mockHandler = new MockHandler(
            [
                new Response($statusCode, [], $responseBody),
            ]
        );

        $config = $this->getTestClient($mockHandler);

        return $config;
    }

    /**
     * Used to queue an exception.
     *
     * @param array $queue
     *
     * @return TestClient
     */
    protected function prepareQueue(array $queue): TestClient
    {
        $mockHandler = new MockHandler($queue);

        $config = $this->getTestClient($mockHandler);

        return $config;
    }

    /**
     * Validate that the request URI contains the following strings.
     *
     * @param TestClient $config
     * @param string[]   $contains
     */
    protected function validateRequestUrl(TestClient $config, array $contains): void
    {
        $trans = $this->lastTransaction($config);

        $url = (string)$trans->request()->getUri();
        foreach ($contains as $contain) {
            $this->assertContains((string)$contain, $url, 'Url need to contain: ' . $contain);
        }
    }

    /**
     * Make sure url contains at least specified parts in contains.
     *
     * @param TestClient    $config
     * @param array|\string $elements
     */
    protected function validateUrl(TestClient $config, $elements): void
    {
        $trans = $this->lastTransaction($config);

        $url = urldecode((string)$trans->request()->getUri());

        if (is_string($elements)) {
            $this->assertContains($elements, $url);

            return;
        }

        $urlElements = explode('/', $url);

        foreach ($elements as $element) {
            $this->assertContains((string)$element, $urlElements, 'Url needs to contain: ' . $element);
        }
    }

    /**
     * Validate the query arguments
     *
     * @param TestClient $client
     * @param string     ...$elements
     */
    protected function validateQuery(TestClient $client, string...$elements): void
    {
        $trans = $this->lastTransaction($client);
        $query = urldecode($trans->request()->getUri()->getQuery());


        $urlElements = explode('&', $query);

        foreach ($elements as $element) {
            $this->assertContains((string)$element, $urlElements, 'Query needs to contain: ' . $element);
        }
    }
}
