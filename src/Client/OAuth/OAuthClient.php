<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 09:42.
 */

namespace ZEROSPAM\Framework\SDK\Client\OAuth;

use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\BaseClient;
use ZEROSPAM\Framework\SDK\Client\Middleware\IRefreshTokenMiddleware;
use ZEROSPAM\Framework\SDK\Config\OAuth\IOAuthConfiguration;
use ZEROSPAM\Framework\SDK\Exception\Middleware\NoMiddlewareSetException;

/**
 * Class OAuthClient
 *
 * Client for OAuth server interaction
 *
 * @package ZEROSPAM\Framework\SDK\Client
 */
class OAuthClient extends BaseClient implements IOAuthClient
{
    protected AccessToken $token;
    /**
     * @var IRefreshTokenMiddleware[]
     */
    private array $refreshTokenMiddlewares = [];

    /**
     * OauthClient constructor.
     *
     * @param IOAuthConfiguration $configuration
     * @param AccessToken $token
     * @param ClientInterface|null $guzzleClient Only set if you want to override the default client
     */
    public function __construct(
        IOAuthConfiguration $configuration,
        AccessToken $token,
        ClientInterface $guzzleClient = null
    ) {
        parent::__construct($configuration, $guzzleClient);
        $this->token = $token;
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
     * UnRegister a refreshToken middleware
     *
     * @param IRefreshTokenMiddleware $middleware
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
            $token = $middleware->handleRefreshToken($token, $this->lastRequest->tries());
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
}
