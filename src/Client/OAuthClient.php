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
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use ZEROSPAM\Framework\SDK\Client\Exception\SDKException;
use ZEROSPAM\Framework\SDK\Client\Exception\TooManyRetriesException;
use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Config\IOAuthConfiguration;
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\Api\BaseResponse;
use ZEROSPAM\Framework\SDK\Response\Api\IResponse;
use ZEROSPAM\Framework\SDK\Response\RateLimit\RateLimitData;
use ZEROSPAM\Framework\SDK\Utils\JSON\JSONParsing;

/**
 * Class OAuthClient.
 *
 * Client for OAuth server interaction
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
    private $middlewares;

    /**
     * OauthClient constructor.
     *
     * @param IOAuthConfiguration  $configuration
     * @param AccessToken          $token
     * @param ClientInterface|null $guzzleClient  Only set if you want to override the default client
     */
    public function __construct(IOAuthConfiguration $configuration, AccessToken $token, ClientInterface $guzzleClient = null)
    {
        $this->configuration = $configuration;
        $this->rateLimit = new RateLimitData();
        $this->token = $token;
        $this->guzzleClient = $guzzleClient;
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
            $this->middlewares[$statusCode][] = $middleware;
        }

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
            if (!isset($this->middlewares[$statusCode])) {
                continue;
            }
            $result = array_filter(
                $this->middlewares[$statusCode],
                function (IMiddleware $currMiddleware) use ($middlewareClass) {
                    return get_class($currMiddleware) != $middlewareClass;
                }
            );

            if (empty($result)) {
                unset($this->middlewares[$statusCode]);
            } else {
                $this->middlewares[$statusCode] = $result;
            }
        }

        return $this;
    }

    /**
     * Refresh token.
     */
    public function refreshToken(): AccessToken
    {
        return $this->token = $this->configuration->refreshAccessToken($this->token);
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
     * Process the given request and return an array containing the results.
     *
     * @param IRequest $request
     *
     * @return IResponse
     */
    public function processRequest(IRequest $request): IResponse
    {
        $request->incrementTries();

        $httpRequest = $this->configuration->getProvider()->getAuthenticatedRequest(
            $request->requestType()->getValue(),
            $request->toUri(),
            $this->token,
            $request->requestOptions()
        );

        try {
            $response = $this->guzzleClient->send($httpRequest);
            $parsedData = JSONParsing::responseToJson($response);

            if (isset($this->middlewares[$response->getStatusCode()])) {
                foreach ($this->middlewares[$response->getStatusCode()] as $middleware) {
                    $parsedData = $middleware->handle($request, $response, $parsedData);
                }
            }
        } catch (BadResponseException $e) {
            /**
             * Check status code.
             */
            $response = $e->getResponse();
            $this->processThrottleData($response);

            if (!isset($this->middlewares[$response->getStatusCode()])) {
                throw new SDKException($e->getMessage(), $e->getCode(), $e);
            }
            $parsedData = JSONParsing::responseToJson($response);

            foreach ($this->middlewares[$response->getStatusCode()] as $middleware) {
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
         * @var BaseResponse
         */
        $data = $request->processResponse($parsedData);

        $this->processThrottleData($response);
        $data->setRateLimit($this->rateLimit);

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
