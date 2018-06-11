<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-11
 * Time: 11:38
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Stackable;

use ZEROSPAM\Framework\SDK\Request\Arguments\RequestArg;

class SubKeyedStackableRequestArg extends RequestArg implements ISubKeyedStackableArgument
{


    /**
     * @var null|string
     */
    private $subKey;

    public function __construct(string $key, string $subKey, string $value)
    {
        parent::__construct($key, $value);
        $this->subKey = $subKey;
    }

    public function getSubKey(): string
    {
        return $this->subKey;
    }
}
