<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 09:43.
 */

namespace ZEROSPAM\Framework\SDK\Config;

use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

abstract class OAuthConfiguration implements IOAuthConfiguration
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
     * @var string
     */
    private $endPoint;

    /**
     * OAuthConfiguration constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUrl
     * @param string $endPoint
     */
    public function __construct(string $clientId, string $clientSecret, string $redirectUrl, string $endPoint)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUrl = $redirectUrl;
        $this->endPoint = $endPoint;
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
     * End point for Requests.
     *
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * Get access token for given code.
     *
     * @param string $code
     *
     * @return AccessToken
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
}
