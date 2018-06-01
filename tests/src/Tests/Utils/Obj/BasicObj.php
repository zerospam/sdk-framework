<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:27
 */

namespace ZEROSPAM\Framework\SDK\Test\Tests\Utils\Obj;

class BasicObj
{

    private $foo;

    /**
     * BasicClass constructor.
     *
     * @param $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }
}
