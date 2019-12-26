<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 17:35
 */

namespace houseorm\Cache;


use houseorm\Cache\Config\CacheConfig;
use houseorm\Cache\Config\CacheConfigInterface;
use houseorm\Cache\Drivers\CacheDriverInterface;
use houseorm\Cache\Drivers\MemoryDriver\MemoryCacheDriver;
use houseorm\Cache\Request\Find\FindCacheRequestInterface;
use houseorm\Cache\Request\Reset\ResetCacheRequestInterface;
use houseorm\Cache\Request\Set\SetCacheRequestInterface;

/**
 * Class Cache
 * @package houseorm\Cache
 */
class Cache implements CacheInterface
{

    /**
     * @var CacheDriverInterface
     */
    private $driver;

    /**
     * Cache constructor.
     * @param CacheConfigInterface $config
     */
    public function __construct(CacheConfigInterface $config)
    {
        switch ($config->getDriver()) {
            case CacheConfig::MEMORY_DRIVER:
            default:
                $this->driver = new MemoryCacheDriver($config);
        }
    }

    /**
     * @param FindCacheRequestInterface $request
     * @return mixed
     */
    public function get(FindCacheRequestInterface $request)
    {
        return $this->driver->get($request);
    }

    /**
     * @param SetCacheRequestInterface $request
     * @return void
     */
    public function set(SetCacheRequestInterface $request)
    {
        $this->driver->set($request);
    }

    /**
     * @param ResetCacheRequestInterface $request
     * @return void
     */
    public function reset(ResetCacheRequestInterface $request)
    {
        $this->driver->reset($request);
    }
}
