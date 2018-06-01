<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 10:43
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware;

use ZEROSPAM\Framework\SDK\Client\IOAuthClient;

trait MiddlewareClient
{

    /**
     * @var IOAuthClient
     */
    private $client;

    /**
     * Set the OAuth Client
     *
     * @param IOAuthClient $client
     *
     * @return $this
     */
    public function setClient(IOAuthClient $client): IMiddleware
    {
        $this->client = $client;

        return $this;
    }
}
