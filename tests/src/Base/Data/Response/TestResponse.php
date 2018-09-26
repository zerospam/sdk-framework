<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 14:19.
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Data\Response;

use Carbon\Carbon;
use ZEROSPAM\Framework\SDK\Response\Api\BaseResponse;

/**
 * Class TestResponse.
 *
 * Test Response
 *
 * @property-read \stdClass $test
 * @property-read Carbon    $test_date
 */
class TestResponse extends BaseResponse
{
    protected $dates
        = [
            'test_date',
        ];

    public function getTestAttribute(): \stdClass
    {
        $test = $this->data['test'];
        $obj = new \stdClass();
        $obj->test = $test;

        return $obj;
    }
}
