<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:35 PM.
 */

namespace ZEROSPAM\Framework\SDK\Request\Api;

use GuzzleHttp\RequestOptions;
use Psr\Http\Message\UriInterface;
use ZEROSPAM\Framework\SDK\Request\Arguments\IArgument;
use ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable\IMergeableArgument;
use ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable\Worker\ArgMerger;
use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\IStackableArgument;
use ZEROSPAM\Framework\SDK\Request\Arguments\Stackable\Worker\ArgCollector;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Utils\Reflection\ReflectionUtil;

/**
 * Class BaseRequest
 *
 * Represent a base request that will be sent to the API Server
 *
 * @package ZEROSPAM\Framework\SDK\Request\Api
 */
abstract class BaseRequest implements IRequest
{
    /**
     * @var IArgument[]
     */
    private $arguments = [];

    /**
     * @var \ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable\Worker\ArgMerger[]
     */
    private $mergeableArguments = [];

    /**
     * @var ArgCollector[]
     */
    private $stackableArguments = [];
    /**
     * @var \ZEROSPAM\Framework\SDK\Response\Api\IResponse
     */
    private $response;

    /** @var int Number of tries of this request */
    private $tries = 0;

    /**
     * @var RequestType
     */
    private $requestTypeOverride;

    /**
     * Add a request argument.
     *
     * @param IArgument $arg
     *
     * @return $this
     */
    public function addArgument(IArgument $arg): IRequest
    {
        if ($arg instanceof IMergeableArgument) {
            if (!isset($this->mergeableArguments[$arg->getKey()])) {
                $this->mergeableArguments[$arg->getKey()] = new ArgMerger($arg::glue());
            }

            $this->mergeableArguments[$arg->getKey()]->addArgument($arg);

            return $this;
        }

        if ($arg instanceof IStackableArgument) {
            if (!isset($this->stackableArguments[$arg->getKey()])) {
                $this->stackableArguments[$arg->getKey()] = new ArgCollector();
            }

            $this->stackableArguments[$arg->getKey()]->addArgument($arg);

            return $this;
        }

        $this->arguments[$arg->getKey()] = $arg;

        return $this;
    }

    /**
     * Remove a request argument.
     *
     * @param IArgument $arg
     *
     * @return $this
     */
    public function removeArgument(IArgument $arg): IRequest
    {
        if ($arg instanceof IMergeableArgument) {
            if (!isset($this->mergeableArguments[$arg->getKey()])) {
                throw new \InvalidArgumentException("This argument doesn't exists");
            }
            $this->mergeableArguments[$arg->getKey()]->removeArgument($arg);

            if ($this->mergeableArguments[$arg->getKey()]->isEmpty()) {
                unset($this->mergeableArguments[$arg->getKey()]);
            }
        } elseif ($arg instanceof IStackableArgument) {
            if (!isset($this->stackableArguments[$arg->getKey()])) {
                throw new \InvalidArgumentException("This argument doesn't exists");
            }
            $this->stackableArguments[$arg->getKey()]->removeArgument($arg);

            if ($this->stackableArguments[$arg->getKey()]->isEmpty()) {
                unset($this->stackableArguments[$arg->getKey()]);
            }
        } else {
            unset($this->arguments[$arg->getKey()]);
        }

        return $this;
    }

    /**
     * Get all the request arguments.
     *
     * @return IArgument[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Get the data used for the route.
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    public function toArray(): array
    {
        return ReflectionUtil::objToSnakeArray($this, $this->blacklistedProperties());
    }

    /**
     * List of properties to not serialize.
     *
     * @return array
     */
    protected function blacklistedProperties()
    {
        static $blacklist = [
            'arguments',
            'response',
            'mergeableArguments',
            'reflectionProperties',
            'tries',
            'requestTypeOverride',
            'stackableArguments',
            'nullableChanged',
        ];

        return $blacklist;
    }

    /**
     * Return the URI of the request.
     *
     * @return UriInterface
     */
    public function toUri(): UriInterface
    {
        $fullUrl = $this->generateUrl();

        return \GuzzleHttp\Psr7\uri_for($fullUrl);
    }

    /**
     * Request type to use for the request.
     *
     * @return RequestType
     */
    final public function requestType(): RequestType
    {
        return $this->requestTypeOverride
            ?: $this->httpType();
    }

    /**
     * Options for this request to be used by the client.
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    public function requestOptions(): array
    {
        /**
         * Don't set any JSON if it's a get request
         */
        if ($this->requestType()->is(RequestType::HTTP_GET())) {
            return [];
        }
        $options = [
            RequestOptions::JSON => $this->toArray(),
        ];

        return $options;
    }

    /**
     * Getter for response.
     *
     * @return \ZEROSPAM\Framework\SDK\Response\Api\IResponse
     */
    public function getResponse(): IResponse
    {
        return $this->response;
    }

    /**
     * @param \ZEROSPAM\Framework\SDK\Response\Api\IResponse $response
     */
    public function setResponse(IResponse $response): void
    {
        $this->response = $response;
    }

    /**
     * Number of tries of the request.
     *
     * @return int
     */
    public function tries(): int
    {
        return $this->tries;
    }

    /**
     * Increment the number of tries and returns it.
     *
     * @return int
     */
    public function incrementTries(): int
    {
        return ++$this->tries;
    }

    /**
     * @return \ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable\Worker\ArgMerger[]
     */
    public function getMergeableArguments(): array
    {
        return $this->mergeableArguments;
    }

    /**
     * Generate the URL
     *
     * Takes care of removing the "include[0]" and replace by "include[]"
     *
     * @return string
     */
    private function generateUrl(): string
    {
        static $re = '/\%5B\d+\%5D/m';
        static $subst = '%5B%5D';

        $query = [];
        /**
         * @var IArgument
         */
        foreach ($this->arguments as $key => $value) {
            $query[$key] = $value->toPrimitive();
        }

        foreach ($this->mergeableArguments as $key => $value) {
            $query[$key] = $value->toPrimitive();
        }

        foreach ($this->stackableArguments as $key => $value) {
            $query[$key] = $value->toArray();
        }

        $queryArg = http_build_query($query, null, '&', PHP_QUERY_RFC3986);

        $fullUrl = $this->routeUrl() . '?' . preg_replace($re, $subst, $queryArg);

        return $fullUrl;
    }
}
