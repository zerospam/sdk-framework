<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 10:41.
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware\Error;

use Psr\Http\Message\ResponseInterface;
use ZEROSPAM\Framework\SDK\Client\BaseClient;
use ZEROSPAM\Framework\SDK\Client\Exception\TooManyRetriesException;
use ZEROSPAM\Framework\SDK\Client\Middleware\IMiddleware;
use ZEROSPAM\Framework\SDK\Request\Api\IRequest;
use ZEROSPAM\Framework\SDK\Response\Api\BaseResponse;

/**
 * Class AuthenticationMiddleware
 *
 * Catch a Authentication failure and try to refresh the access_token
 *
 * @package ZEROSPAM\Framework\SDK\Client\Middleware\Error
 */
class AuthenticationMiddleware implements IMiddleware
{
    const MAX_TRIES = 3;
    protected BaseClient $client;

    /**
     *
     * @param BaseClient $client
     *
     * @return $this
     */
    public function setClient(BaseClient $client): IMiddleware
    {
        $this->client = $client;

        return $this;
    }


    /**
     * Which status error code does this middleware manage.
     *
     * @return array
     */
    public static function statusCode(): array
    {
        return [401];
    }

    public function handle(IRequest $request, ResponseInterface $httpResponse, array $parsedData): array
    {
        if ($request->tries() > self::MAX_TRIES) {
            throw new TooManyRetriesException(sprintf('Tried to re-auth more than %d times.', self::MAX_TRIES));
        }
        $this->client->refreshToken();
        $response = $this->client->processRequest($request);

        if ($response instanceof BaseResponse) {
            return $response->getRawData();
        }

        return $response->data();
    }
}
