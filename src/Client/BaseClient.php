<?php

namespace ZEROSPAM\Framework\SDK\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use ZEROSPAM\Framework\SDK\Client\Exception\SDKException;
use ZEROSPAM\Framework\SDK\Client\Exception\TooManyRetriesException;
use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IPreRequestMiddleware;
use ZEROSPAM\Framework\SDK\Client\OAuth\OAuthClient;
use ZEROSPAM\Framework\SDK\Config\BaseConfiguration;
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\Api\BaseResponse;
use ZEROSPAM\Framework\SDK\Response\Api\IRateLimitedResponse;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Response\RateLimit\RateLimitData;
use ZEROSPAM\Framework\SDK\Utils\JSON\JSONParsing;

abstract class BaseClient implements IClient
{
    protected BaseConfiguration $configuration;
    protected RateLimitData $rateLimit;
    protected ?ClientInterface $guzzleClient;
    protected IRequest $lastRequest;
    /**
     * @var IMiddleware[][]
     */
    protected array $postRequestMiddlewares = [];
    /**
     * @var IPreRequestMiddleware[]
     */
    protected array $preRequestMiddlewares = [];


    /**
     * OauthClient constructor.
     *
     * @param BaseConfiguration $configuration
     * @param ClientInterface|null $guzzleClient Only set if you want to override the default client
     */
    public function __construct(BaseConfiguration $configuration, ClientInterface $guzzleClient = null)
    {
        $this->configuration = $configuration;
        $this->rateLimit     = new RateLimitData();
        $this->guzzleClient  = $guzzleClient;
        if ($this->guzzleClient == null) {
            $this->guzzleClient = new Client(['base_uri' => $this->configuration->getBaseUri()]);
        }
        foreach ($this->configuration->defaultMiddlewares() as $middleware) {
            $this->registerMiddleware($middleware);
        }
        foreach ($this->configuration->defaultPreRequestMiddlewares() as $middleware) {
            $this->registerPreRequestMiddleware($middleware);
        }
    }

    /**
     * Register the given middleware.
     *
     * @param IMiddleware $middleware
     *
     * @return self
     */
    public function registerMiddleware(IMiddleware $middleware): self
    {
        $middleware->setClient($this);
        foreach ($middleware::statusCode() as $statusCode) {
            $this->postRequestMiddlewares[$statusCode][] = $middleware;
        }

        return $this;
    }

    /**
     * Register a pre request middleware
     *
     * @param IPreRequestMiddleware $middleware
     *
     * @return self
     */
    public function registerPreRequestMiddleware(IPreRequestMiddleware $middleware): self
    {
        $this->preRequestMiddlewares[get_class($middleware)] = $middleware;

        return $this;
    }

    /**
     * Unregister the middleware.
     *
     * @param IMiddleware $middleware
     *
     * @return self
     */
    public function unregisterMiddleware(IMiddleware $middleware): self
    {
        $middlewareClass = get_class($middleware);
        foreach ($middleware::statusCode() as $statusCode) {
            if (!isset($this->postRequestMiddlewares[$statusCode])) {
                continue;
            }
            $result = array_filter(
                $this->postRequestMiddlewares[$statusCode],
                function (IMiddleware $currMiddleware) use ($middlewareClass) {
                    return get_class($currMiddleware) != $middlewareClass;
                }
            );

            if (empty($result)) {
                unset($this->postRequestMiddlewares[$statusCode]);
            } else {
                $this->postRequestMiddlewares[$statusCode] = $result;
            }
        }

        return $this;
    }

    /**
     * UnRegister a pre request middleware
     *
     * @param IPreRequestMiddleware $middleware
     *
     * @return self
     */
    public function unregisterPreRequestMiddleware(IPreRequestMiddleware $middleware): IClient
    {
        unset($this->preRequestMiddlewares[get_class($middleware)]);

        return $this;
    }

    /**
     * Get linked configuration
     *
     * @return BaseConfiguration
     */
    public function getConfiguration(): BaseConfiguration
    {
        return $this->configuration;
    }


    /**
     * Process the given request and return an array containing the results.
     *
     * @param IRequest $request
     *
     * @return IResponse
     */
    public function processRequest(IRequest $request): IResponse
    {
        $this->lastRequest = $request;
        foreach ($this->preRequestMiddlewares as $middleware) {
            $middleware->handle($request);
        }
        $request->incrementTries();

        /**
         * TODO:: fix smelly code : token is only used in child class @see OAuthClient
         */
        $configurationHeaders = $this->configuration->defaultHeaders($this->token ?? null);
        $options = $request->requestOptions();
        if (isset($options[RequestOptions::HEADERS])) {
            $options[RequestOptions::HEADERS] = array_merge($options[RequestOptions::HEADERS], $configurationHeaders);
        } else {
            $options[RequestOptions::HEADERS] = $configurationHeaders;
        }

        try {
            $response   = $this->guzzleClient->request($request->httpType()->getValue(), $request->toUri(), $options);
            $parsedData = JSONParsing::responseToJson($response);

            if (isset($this->postRequestMiddlewares[$response->getStatusCode()])) {
                foreach ($this->postRequestMiddlewares[$response->getStatusCode()] as $middleware) {
                    $parsedData = $middleware->handle($request, $response, $parsedData);
                }
            }
        } catch (BadResponseException $e) {
            /**
             * Check status code.
             */
            $response = $e->getResponse();
            $this->processThrottleData($response);

            if (!isset($this->postRequestMiddlewares[$response->getStatusCode()])) {
                throw new SDKException($e->getMessage(), $e->getCode(), $e);
            }
            $parsedData = JSONParsing::responseToJson($response);

            foreach ($this->postRequestMiddlewares[$response->getStatusCode()] as $middleware) {
                $parsedData = $middleware->handle($request, $response, $parsedData);
            }
        } catch (RequestException $e) {
            if ($request->requestType()->is(RequestType::HTTP_GET())
                && $request->tries() < 3) {
                return $this->processRequest($request);
            }

            throw new TooManyRetriesException($e->getMessage(), $e->getCode(), $e);
        } catch (GuzzleException $e) {
            throw new SDKException($e->getMessage(), $e->getCode(), $e);
        }

        /**
         * @var $data BaseResponse
         */
        $data = $request->processResponse($parsedData);

        if ($data instanceof BaseResponse) {
            $data->setRawData($parsedData);
        }

        if ($data instanceof IRateLimitedResponse) {
            $this->processThrottleData($response);
            $data->setRateLimit($this->rateLimit);
        }

        $request->setResponse($data);

        return $data;
    }

    /**
     * Process the rate limit.
     *
     * @param ResponseInterface $response
     */
    private function processThrottleData(ResponseInterface $response)
    {
        if (empty($rateLimit = $response->getHeader('X-RateLimit-Limit'))) {
            return;
        }

        if (empty($remaining = $response->getHeader('X-RateLimit-Remaining'))) {
            return;
        }

        if (!empty($reset = $response->getHeader('X-RateLimit-Reset'))) {
            $this->rateLimit->setEndOfThrottle(intval($reset[0]));
        } else {
            $this->rateLimit->setEndOfThrottle(null);
        }

        $this->rateLimit->setMaxPerMinute(intval($rateLimit[0]))->setRemaining(intval($remaining[0]));
    }
}
