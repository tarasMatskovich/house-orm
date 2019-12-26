<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 26.12.2019
 * Time: 18:04
 */

namespace houseorm\Cache\Request\Reset;

/**
 * Interface ResetCacheRequestInterface
 * @package houseorm\Cache\Request\Reset
 */
interface ResetCacheRequestInterface
{

    /**
     * @return string
     */
    public function getTarget();

    /**
     * @return mixed
     */
    public function getPrimaryKey();

}
