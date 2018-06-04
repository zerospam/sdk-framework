<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 10:55.
 */

namespace ZEROSPAM\Framework\SDK\Client\Exception;

use ZEROSPAM\Framework\SDK\Client\Middleware\Error\AuthenticationMiddleware;

/**
 * Class TooManyRetriesException.
 *
 * When the same request has been retried multiple time without success
 *
 * @see     AuthenticationMiddleware
 */
class TooManyRetriesException extends SDKException
{
}
