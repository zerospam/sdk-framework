<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 09:42.
 */

namespace ZEROSPAM\Framework\SDK\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use ZEROSPAM\Framework\SDK\Client\Exception\SDKException;
use ZEROSPAM\Framework\SDK\Client\Exception\TooManyRetriesException;
use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IPreRequestMiddleware;
use ZEROSPAM\Framework\SDK\Client\Middleware\IRefreshTokenMiddleware;
use ZEROSPAM\Framework\SDK\Config\IOAuthConfiguration;
use ZEROSPAM\Framework\SDK\Exception\Middleware\NoMiddlewareSetException;
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\Api\BaseResponse;
use ZEROSPAM\Framework\SDK\Response\Api\IRateLimitedResponse;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Response\RateLimit\RateLimitData;
use ZEROSPAM\Framework\SDK\Utils\JSON\JSONParsing;

/**
 * Class OAuthClient
 *
 * Client for OAuth server interaction
 *
 * @package ZEROSPAM\Framework\SDK\Client
 */
class OAuthClient implements IOAuthClient
{
    /**
     * @var IOAuthConfiguration
     */
    private $configuration;

    /**
     * @var RateLimitData
     */
    private $rateLimit;
    /**
     * @var ClientInterface
     */
    private $guzzleClient;
    /**
     * @var AccessToken
     */
    private $token;

    /**
     * @var \ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware[][]
     */
    private $postRequestMiddlewares = [];
    /**
     * @var IPreRequestMiddleware[]
     */
    private $preRequestMiddlewares = [];

    /**
     * @var IRefreshTokenMiddleware[]
     */
    private $refreshTokenMiddlewares = [];

    /**
     * OauthClient constructor.
     *
     * @param IOAuthConfiguration  $configuration
     * @param AccessToken          $token
     * @param ClientInterface|null $guzzleClient Only set if you want to override the default client
     */
    public function __construct(IOAuthConfiguration $configuration, AccessToken $token, ClientInterface $guzzleClient = null)
    {
        $this->configuration = $configuration;
        $this->rateLimit     = new RateLimitData();
        $this->token         = $token;
        $this->guzzleClient  = $guzzleClient;
        if ($this->guzzleClient == null) {
            $this->guzzleClient = new Client(['base_uri' => $this->configuration->getEndPoint()]);
        }
    }

    /**
     * Register the given middleware.
     *
     * @param \ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function registerMiddleware(IMiddleware $middleware): IOAuthClient
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
     * @return IOAuthClient
     */
    public function registerPreRequestMiddleware(IPreRequestMiddleware $middleware): IOAuthClient
    {
        $this->preRequestMiddlewares[get_class($middleware)] = $middleware;

        return $this;
    }

    /**
     * Register a middleware to take care of refresh token
     *
     * @param IRefreshTokenMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function registerRefreshTokenMiddleware(IRefreshTokenMiddleware $middleware): IOAuthClient
    {
        $middleware->setClient($this);
        $this->refreshTokenMiddlewares[get_class($middleware)] = $middleware;

        return $this;
    }

    /**
     * Unregister the middleware.
     *
     * In fact, all middleware having the same class
     *
     * @param \ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function unregisterMiddleware(IMiddleware $middleware): IOAuthClient
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
     * @return IOAuthClient
     */
    public function unregisterPreRequestMiddleware(IPreRequestMiddleware $middleware): IOAuthClient
    {
        unset($this->preRequestMiddlewares[get_class($middleware)]);

        return $this;
    }

    /**
     * UnRegister a refreshToken middleware
     *
     * @param IPreRequestMiddleware $middleware
     *
     * @return IOAuthClient
     */
    public function unregisterRefreshTokenMiddleware(IRefreshTokenMiddleware $middleware): IOAuthClient
    {
        unset($this->refreshTokenMiddlewares[get_class($middleware)]);

        return $this;
    }

    /**
     * Refresh token.
     *
     * @throws NoMiddlewareSetException
     */
    public function refreshToken(): AccessToken
    {
        if (empty($this->refreshTokenMiddlewares)) {
            throw new NoMiddlewareSetException('No refresh token middleware present.');
        }
        $token = $this->token;
        foreach ($this->refreshTokenMiddlewares as $middleware) {
            $token = $middleware->handleRefreshToken($token);
        }

        return $this->token = $token;
    }

    /**
     * Currently used access token.
     *
     * @return AccessToken
     */
    public function getToken(): AccessToken
    {
        return $this->token;
    }

    /**
     * Set the AccessToken
     *
     * @param AccessToken $token
     */
    public function setToken(AccessToken $token): void
    {
        $this->token = $token;
    }

    /**
     * Get linked configuration
     *
     * @return IOAuthConfiguration
     */
    public function getConfiguration(): IOAuthConfiguration
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
        foreach ($this->preRequestMiddlewares as $middleware) {
            $middleware->handle($request);
        }
        $request->incrementTries();

        $headers = $this->configuration->getProvider()->getHeaders($this->token);

        $options = $request->requestOptions();
        if (isset($options[RequestOptions::HEADERS])) {
            $options[RequestOptions::HEADERS] = array_merge($options[RequestOptions::HEADERS], $headers);
        } else {
            $options[RequestOptions::HEADERS] = $headers;
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
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw new SDKException($e->getMessage(), $e->getCode(), $e);
        }

        /**
         * @var $data BaseResponse
         */
        $data = $request->processResponse($parsedData);

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
