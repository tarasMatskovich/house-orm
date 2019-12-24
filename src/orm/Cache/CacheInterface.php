<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 17:34
 */

namespace houseorm\Cache;

use houseorm\Cache\Request\Find\FindCacheRequestInterface;

/**
 * Interface CacheInterface
 * @package houseorm\Cache
 */
interface CacheInterface
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
