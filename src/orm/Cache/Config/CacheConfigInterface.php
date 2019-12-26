<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 26.12.2019
 * Time: 16:35
 */

namespace houseorm\Cache\Config;

/**
 * Interface CacheConfigInterface
 * @package houseorm\Cache\Config
 */
interface CacheConfigInterface
{

    /**
     * @return string
     */
    public function getDriver();

    /**
     * @return int
     */
    public function getLifeTime();

    /**
     * @return string
     */
    public function getHost();

    /**
     * @return string
     */
    public function getUser();

    /**
     * @return string
     */
    public function getPassword();

}
