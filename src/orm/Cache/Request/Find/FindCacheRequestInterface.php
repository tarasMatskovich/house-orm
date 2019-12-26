<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 18:25
 */

namespace houseorm\Cache\Request\Find;

/**
 * Interface CacheRequestInterface
 * @package houseorm\Cache\Request
 */
interface FindCacheRequestInterface
{

    /**
     * @return string
     */
    public function getTarget();

    /**
     * @return mixed|null
     */
    public function getPrimaryKey();

}
