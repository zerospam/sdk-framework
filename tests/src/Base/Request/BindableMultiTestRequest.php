<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 15:39.
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Request;

class BindableMultiTestRequest extends BindableTestRequest
{
    public function baseRoute(): string
    {
        return 'test/:testId/nice/:niceId/super/:niceId';
    }
}
