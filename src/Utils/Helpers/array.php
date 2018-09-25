<?php
/**
 * Created by PhpStorm.
 * User: pbb
 * Date: 25/09/18
 * Time: 2:51 PM
 */

if (!function_exists('array_map_r')) {

    /**
     * @param $callback
     * @param $input
     *
     * @return array
     */
    function array_map_r(Closure $callback, $input): array
    {
        $output = [];
        foreach ($input as $key => $data) {
            if (is_array($data)) {
                $output[$key] = array_map_r($callback, $data);
            } else {
                $output[$key] = $callback($data);
            }
        }
        return $output;
    }
}