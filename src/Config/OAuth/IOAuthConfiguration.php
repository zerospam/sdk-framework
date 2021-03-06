<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 09:50.
 */

namespace ZEROSPAM\Framework\SDK\Config\OAuth;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use ZEROSPAM\Framework\SDK\Client\OAuth\OAuthClient;
use ZEROSPAM\Framework\SDK\Config\IBaseConfiguration;

/**
 * Interface IOAuthConfiguration
 *
 * Configuration for an OAuthClient
 *
 * @see     OAuthClient
 * @package ZEROSPAM\Framework\SDK\Config
 */
interface IOAuthConfiguration extends IBaseConfiguration
{
    /**
     * Get the redirect URL.
     *
     * @return string
     */
    public function getRedirectUrl(): string;

    /**
     * Get access token for given code.
     *
     * @param string $code
     *
     * @return AccessToken
     */
    public function getAccessToken(string $code): AccessToken;

    /**
     * Give a new access token refreshed.
     *
     * @param AccessToken $token
     *
     * @return AccessToken
     */
    public function refreshAccessToken(AccessToken $token): AccessToken;

    /**
     * Use the refresh token to get a new access token.
     *
     * @param string $refreshToken
     *
     * @return AccessToken
     */
    public function refreshToken(string $refreshToken): AccessToken;

    /**
     * Get a OAuthProvider.
     *
     * @return AbstractProvider
     */
    public function getProvider(): AbstractProvider;
}
