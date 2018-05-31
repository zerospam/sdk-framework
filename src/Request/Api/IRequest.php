<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 30/05/18
 * Time: 4:49 PM
 */

namespace ZEROSPAM\Framework\SDK\Request\Api;


use Psr\Http\Message\UriInterface;
use ZEROSPAM\Framework\SDK\Request\Arguments\IArgument;
use ZEROSPAM\Framework\SDK\Request\Arguments\Mergeable\ArgMerger;
use ZEROSPAM\Framework\SDK\Request\Type\RequestType;
use ZEROSPAM\Framework\SDK\Response\IResponse;
use ZEROSPAM\Framework\SDK\Utils\Contracts\Arrayable;

interface IRequest extends Arrayable
{

    /**
     * The url of the route
     *
     * @return string
     */
    public function routeUrl(): string;


    /**
     * Type of request
     *
     * @return RequestType
     */
    public function httpType(): RequestType;

    /**
     * Process the data that is in the response
     *
     * @param array $jsonResponse
     *
     * @return IResponse
     */
    public function processResponse(array $jsonResponse): IResponse;

    /**
     * Add a request argument
     *
     * @param IArgument $arg
     *
     * @return $this
     */
    public function addArgument(IArgument $arg): IRequest;

    /**
     * Remove a request argument
     *
     * @param IArgument $arg
     *
     * @return $this
     */
    public function removeArgument(IArgument $arg): IRequest;

    /**
     * Get all the request arguments
     *
     * @return IArgument[]
     */
    public function getArguments(): array;

    /**
     * Return the URI of the request
     *
     * @return UriInterface
     */
    public function toUri();

    /**
     * Getter for response
     *
     * @return IResponse
     */
    public function getResponse(): IResponse;

    /**
     * @param IResponse $response
     */
    public function setResponse(IResponse $response): void;

    /**
     * Options for this request to be used by the client
     *
     * @return array
     */
    public function requestOptions();

    /**
     * Get the argument that can be merged with each other
     *
     * @return ArgMerger[]
     */
    public function getMergeableArguments(): array;

    /**
     * Number of tries of the request
     *
     * @return int
     */
    public function tries();

    /**
     * Increment the number of tries and returns it
     *
     * @return int
     */
    public function incrementTries();

    /**
     * Request type to use for the request
     *
     * @return RequestType
     */
    public function requestType();

}