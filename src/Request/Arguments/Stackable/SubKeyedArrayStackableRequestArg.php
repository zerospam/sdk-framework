<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 25/09/18
 * Time: 9:30 AM
 */

namespace ZEROSPAM\Framework\SDK\Request\Arguments\Stackable;


use ZEROSPAM\Framework\SDK\Request\Arguments\RequestArg;
use ZEROSPAM\Framework\SDK\Utils\Contracts\PrimalValued;

/**
 * Class SubKeyedArrayStackableRequestArg
 *
 * Implementation for arguments using Freshbooks specific format: ex. ?search[statusids][]=1&search[statusids][]=2
 *
 * @package ZEROSPAM\Framework\SDK\Request\Arguments\Stackable
 */
class SubKeyedArrayStackableRequestArg extends RequestArg implements IStackableArrayArgument
{
    /**
     * @var null|string
     */
    private $subKey;

    public function __construct(string $key, string $subKey, array $value)
    {
        parent::__construct($key, $value);
        $this->subKey = $subKey;
    }

    /**
     * @return string
     */
    public function getSubKey(): string
    {
        return $this->subKey;
    }

    /**
     * @return array
     */
    public function toPrimitive(): array
    {
        return array_map_r(function ($data) {
            return $data instanceof PrimalValued
                ? $data->toPrimitive()
                : $data;
        }, $this->value);
    }
}