<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:37 PM
 */

namespace ZEROSPAM\Framework\SDK\Request\Type;

use MabeEnum\Enum;

/**
 * Class RequestType
 *
 * Type of request
 * @method static RequestType HTTP_POST()
 * @method static RequestType HTTP_GET()
 * @method static RequestType HTTP_PUT()
 * @method static RequestType HTTP_HEAD()
 * @method static RequestType HTTP_DELETE()
 * @method static RequestType HTTP_PATCH()
 *
 * @package ProvulusSDK\Client\Routes
 */
class RequestType extends Enum
{

    const HTTP_GET    = 'GET';
    const HTTP_POST   = 'POST';
    const HTTP_PUT    = 'PUT';
    const HTTP_HEAD   = 'HEAD';
    const HTTP_DELETE = 'DELETE';
    const HTTP_PATCH  = 'PATCH';
}
