<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 17:39
 */

namespace houseorm\Cache\Drivers;

use houseorm\Cache\Request\Find\FindCacheRequestInterface;

/**
 * Interface CacheDriverInterface
 * @package houseorm\Cache\Drivers
 */
interface CacheDriverInterface
{

    /**
     * @param FindCacheRequestInterface $request
     * @return mixed
     */
    public function get(FindCacheRequestInterface $request);

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value);

}
