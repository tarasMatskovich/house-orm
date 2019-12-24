<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 17:40
 */

namespace houseorm\Cache\Drivers\MemoryDriver;

use houseorm\Cache\Drivers\CacheDriverInterface;
use houseorm\Cache\Drivers\MemoryDriver\Registry\Registry;
use houseorm\Cache\Drivers\MemoryDriver\Registry\RegistryInterface;
use houseorm\Cache\Request\Find\FindCacheRequestInterface;
use houseorm\config\ConfigInterface;

/**
 * Class MemoryCacheDriver
 * @package houseorm\Cache\Drivers
 */
class MemoryCacheDriver implements CacheDriverInterface
{

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * MemoryCacheDriver constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->registry = new Registry();
    }

    /**
     * @param FindCacheRequestInterface $request
     * @return mixed
     */
    public function get(FindCacheRequestInterface $request)
    {
        return null;
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value)
    {
        // TODO: Implement set() method.
    }
}
