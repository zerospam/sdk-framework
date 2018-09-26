<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-09-26
 * Time: 11:41
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Data\Response\Data;

use Carbon\Carbon;

class TestDataObject
{

    /**
     * @var Carbon
     */
    private $testDate;

    /**
     * @param Carbon $testDate
     *
     * @return $this
     */
    public function setTestDate(Carbon $testDate): TestDataObject
    {
        $this->testDate = $testDate;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getTestDate(): Carbon
    {
        return $this->testDate;
    }
}
