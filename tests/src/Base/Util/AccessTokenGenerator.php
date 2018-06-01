<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 14:15
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Util;

use Carbon\Carbon;
use League\OAuth2\Client\Token\AccessToken;

final class AccessTokenGenerator
{

    private function __construct()
    {
    }

    /**
     * Generate an access token
     *
     * @return AccessToken
     */
    public static function generateAccessToken(): AccessToken
    {
        $accessToken  = uniqid();
        $refreshToken = uniqid();
        $expire       = Carbon::now()->addDay(14)->timestamp;
        $now          = Carbon::now()->timestamp;
        $json
                      = <<<JSON
{
  "token_type": "bearer",
  "scope": "profile:write",
  "created_at": $now,
  "access_token": "$accessToken",
  "refresh_token": "$refreshToken",
  "expires": $expire
}
JSON;

        return new AccessToken(json_decode($json));
    }
}
