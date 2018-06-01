<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:28
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj;

class BasicObjEnum
{

    /**
     * @var BasicEnum
     */
    private $enum;

    /**
     * BasicObjEnum constructor.
     *
     * @param BasicEnum $enum
     */
    public function __construct(BasicEnum $enum)
    {
        $this->enum = $enum;
    }
}
