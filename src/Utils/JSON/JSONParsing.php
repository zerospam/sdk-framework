<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 11:11.
 */

namespace ZEROSPAM\Framework\SDK\Utils\JSON;

use Psr\Http\Message\ResponseInterface;

/**
 * Class JSONParsing.
 *
 * Parse a request JSON
 */
final class JSONParsing
{
    private function __construct()
    {
    }

    /**
     * Parse the content of a response.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    public static function responseToJson(ResponseInterface $response)
    {
        $contents = $response->getBody()->getContents();
        if (empty($contents)) {
            return [];
        }

        return \GuzzleHttp\json_decode($contents, true);
    }
}
