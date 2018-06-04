<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:28.
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj;

class BasicObjArrayEnum
{
    /**
     * @var array|BasicEnum[]
     */
    private $enumArray = [];

    /**
     * BasicObjArrayEnum constructor.
     *
     * @param array|BasicEnum[] $enumArray
     */
    public function __construct($enumArray)
    {
        $this->enumArray = $enumArray;
    }
}
