<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 13:53
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Container;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Transaction
{

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var array
     */
    private $options;
    /**
     * @var GuzzleException|null
     */
    private $error;

    /**
     * Transaction constructor.
     *
     * @param RequestInterface  $request
     * @param array             $options
     * @param ResponseInterface $response
     * @param GuzzleException   $error
     */
    public function __construct(
        RequestInterface $request,
        array $options,
        ResponseInterface $response = null,
        GuzzleException $error = null
    ) {

        $this->request  = $request;
        $this->response = $response;
        $this->options  = $options;
        $this->error    = $error;
    }

    /**
     * Getter for request
     *
     * @return RequestInterface
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Getter for response
     *
     * @return ResponseInterface|null
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Getter for options
     *
     * @return array
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * If an exception got triggered
     *
     * @return GuzzleException|null
     */
    public function error()
    {
        return $this->error;
    }
}
