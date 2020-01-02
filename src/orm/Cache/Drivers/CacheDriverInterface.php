<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 17:39
 */

namespace houseorm\Cache\Drivers;

use houseorm\Cache\Request\Find\FindCacheRequestInterface;
use houseorm\Cache\Request\Reset\ResetCacheRequestInterface;
use houseorm\Cache\Request\Set\SetCacheRequestInterface;

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
     * @param SetCacheRequestInterface $request
     * @return void
     */
    public function set(SetCacheRequestInterface $request);

    /**
     * @param ResetCacheRequestInterface $request
     * @return void
     */
    public function reset(ResetCacheRequestInterface $request);

}
