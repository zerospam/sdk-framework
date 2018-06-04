<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:25.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj;

use MabeEnum\Enum;
use ZEROSPAM\Framework\SDK\Utils\Contracts\Impl\PrimalValuedEnumTrait;
use ZEROSPAM\Framework\SDK\Utils\Contracts\PrimalValued;

/**
 * Class BasicEnum.
 *
 * @method static BasicEnum TEST()
 */
class BasicEnum extends Enum implements PrimalValued
{
    use PrimalValuedEnumTrait;

    const TEST = 'test';
}
