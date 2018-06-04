<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:26.
 */

namespace ZEROSPAM\Framework\SDK\Utils\Contracts\Impl;

trait PrimalValuedEnumTrait
{
    public function toPrimitive()
    {
        return $this->getValue();
    }
}
