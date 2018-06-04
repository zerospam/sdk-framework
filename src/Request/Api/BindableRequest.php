<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-05-31
 * Time: 16:26.
 */

namespace ZEROSPAM\Framework\SDK\Request\Api;

use ZEROSPAM\Framework\SDK\Utils\Contracts\PrimalValued;
use ZEROSPAM\Framework\SDK\Utils\Str;

/**
 * Class BindableRequest
 *
 * To be used when you need bindings in your api route url.
 *
 * @package ZEROSPAM\Framework\SDK\Request\Api
 */
abstract class BindableRequest extends BaseRequest
{
    /**
     * @var array
     */
    private $routeBindings = [];

    protected function blacklistedProperties()
    {
        $blacklist   = parent::blacklistedProperties();
        $blacklist[] = 'routeBindings';

        return $blacklist;
    }

    /**
     * Set a new binding.
     *
     * @param      $key
     * @param      $value
     * @param bool $override
     */
    protected function addBinding($key, $value, $override = false): void
    {
        //addBinding(orgId, 5)
        if (isset($this->routeBindings[$key]) && !$override) {
            throw new \InvalidArgumentException("You can't override the key: $key");
        }

        if ($value instanceof PrimalValued) {
            $value = $value->toPrimitive();
        }

        if (is_object($value) || is_array($value)) {
            throw new \InvalidArgumentException("You can't use an object or an array as binding");
        }

        $this->routeBindings[$key] = $value;
    }

    /**
     * Is the binding set.
     *
     * @param $key
     *
     * @return bool
     */
    protected function hasBinding($key): bool
    {
        return isset($this->routeBindings[$key]);
    }

    public function routeUrl(): string
    {
        $bindingsPatterns = array_map(
            function ($item) {
                return ':' . $item;
            },
            array_keys($this->routeBindings)
        );

        $url = str_replace($bindingsPatterns, array_values($this->routeBindings), $this->baseRoute());
        if (Str::contains($url, ':')) {
            throw new \InvalidArgumentException('One or more bindings haven\'t been set.');
        }

        return $url;
    }

    /**
     * Base route without binding.
     *
     * @return string
     */
    abstract public function baseRoute(): string;
}
