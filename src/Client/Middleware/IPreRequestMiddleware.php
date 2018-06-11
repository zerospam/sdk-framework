<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-11
 * Time: 15:42
 */

namespace ZEROSPAM\Framework\SDK\Client\Middleware;

use ZEROSPAM\Framework\SDK\Request\Api\IRequest;

/**
 * Interface IPreRequestMiddleware
 *
 * Change the request before processing it
 *
 * @package ZEROSPAM\Framework\SDK\Client\Middleware
 */
interface IPreRequestMiddleware
{

    /**
     * Handle the request before processing
     *
     * @param IRequest $request
     *
     */
    public function handle(IRequest $request): void;
}
