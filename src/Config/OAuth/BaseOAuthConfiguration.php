<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 09:43.
 */

namespace ZEROSPAM\Framework\SDK\Config\OAuth;

use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Config\BaseConfiguration;

/**
 * Class OAuthConfiguration
 *
 * Helper for an OAuthConfiguration.
 *
 * You need to implement the providerClass by pointing it to your wanted OAuthProvider
 *
 * @see     AbstractProvider
 * @package ZEROSPAM\Framework\SDK\Config
 */
abstract class BaseOAuthConfiguration extends BaseConfiguration implements IOAuthConfiguration
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * OAuthConfiguration constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUrl
     * @param string $endPoint
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUrl,
        string $endPoint)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUrl  = $redirectUrl;

        parent::__construct($endPoint);
    }

    /**
     * Class to use for the provider.
     *
     * @return string
     */
    abstract protected function providerClass(): string;

    /**
     * Get a OAuthProvider.
     *
     * @return AbstractProvider
     */
    public function getProvider(): AbstractProvider
    {
        $class = $this->providerClass();

        return new $class([
            'clientId'     => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'redirectUri'  => $this->redirectUrl,
        ]);
    }

    /**
     * Get the redirect URL.
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * Get access token for given code.
     *
     * @param string $code
     *
     * @return AccessToken
     * @throws IdentityProviderException
     */
    public function getAccessToken(string $code): AccessToken
    {
        return $this->getProvider()->getAccessToken(
            new AuthorizationCode(),
            [
                'code' => $code,
            ]
        );
    }

    /**
     * Give a new access token refreshed.
     *
     * @param AccessToken $token
     *
     * @return AccessToken
     * @throws IdentityProviderException
     */
    public function refreshAccessToken(AccessToken $token): AccessToken
    {
        return $this->refreshToken($token->getRefreshToken());
    }

    /**
     * Use the refresh token to get a new access token.
     *
     * @param string $refreshToken
     *
     * @return AccessToken
     * @throws IdentityProviderException
     */
    public function refreshToken(string $refreshToken): AccessToken
    {
        return $this->getProvider()->getAccessToken(
            new RefreshToken(),
            [
                'refresh_token' => $refreshToken,
            ]
        );
    }

    /**
     * @param string|null $token
     * @return array
     */
    public function defaultHeaders(string $token = null): array
    {
        if (is_null($token)) {
            return [];
        }

        return $this->getProvider()->getHeaders($token);
    }
}
