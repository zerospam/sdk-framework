<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 10:43
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware;

use ZEROSPAM\Framework\SDK\Client\OAuthClient;

trait MiddlewareClient
{

    /**
     * @var OAuthClient
     */
    private $client;

    /**
     * Set the OAuth Client
     *
     * @param OAuthClient $client
     *
     * @return $this
     */
    public function setClient(OAuthClient $client): IMiddleware
    {
        $this->client = $client;

        return $this;
    }
}
