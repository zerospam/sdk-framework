<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 10:59.
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware;

use Psr\Http\Message\ResponseInterface;
use ZEROSPAM\Framework\SDK\Client\IOAuthClient;
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;

/**
 * Interface IMiddleware
 *
 * Intercept the response given by the API in the case of a match with the set status code.
 *
 * @package ZEROSPAM\Framework\SDK\Client\Middleware
 */
interface IMiddleware
{
    /**
     * Set the OAuth Client.
     *
     * @param IOAuthClient $client
     *
     * @return $this
     */
    public function setClient(IOAuthClient $client): self;

    /**
     * Which status error code does this middleware manage.
     *
     * @return array
     */
    public static function statusCode(): array;

    /**
     * Handle the request/response.
     *
     * Return an array with the response data
     *
     * @param IRequest          $request
     * @param ResponseInterface $httpResponse
     * @param array             $parsedData
     *
     * @return array
     */
    public function handle(IRequest $request, ResponseInterface $httpResponse, array $parsedData): array;
}
