<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 17:35
 */

namespace houseorm\Cache;


use houseorm\Cache\Drivers\CacheDriverInterface;
use houseorm\Cache\Drivers\MemoryDriver\MemoryCacheDriver;
use houseorm\Cache\Request\Find\FindCacheRequestInterface;
use houseorm\config\ConfigInterface;
use houseorm\mapper\collection\DomainCollection;

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
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->driver = new MemoryCacheDriver($config);
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
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value)
    {
        // TODO: Implement set() method.
    }
}
