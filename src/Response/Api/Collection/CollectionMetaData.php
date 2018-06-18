<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-18
 * Time: 11:38
 */

namespace ZEROSPAM\Framework\SDK\Response\Api\Collection;

use ZEROSPAM\Framework\SDK\Utils\Str;

/**
 * Class CollectionPagination
 *
 * @property-read int $total
 * @property-read int $count
 * @property-read int $perPage
 * @property-read int $currentPage
 * @property-read int $totalPages
 * @package ProvulusSDK\Client\Response\Collection
 */
class CollectionMetaData
{

    private $metaDat = [];

    /**
     * CollectionPagination constructor.
     *
     * @param array $pagination
     */
    public function __construct(array $pagination)
    {
        $this->metaDat = $pagination;
    }


    function __isset($name)
    {
        $name = Str::snake($name);

        return isset($this->metaDat[$name]);
    }


    function __get($name)
    {
        if (isset($this->{$name})) {
            $name = Str::snake($name);

            return $this->metaDat[$name];
        }

        return null;
    }
}
