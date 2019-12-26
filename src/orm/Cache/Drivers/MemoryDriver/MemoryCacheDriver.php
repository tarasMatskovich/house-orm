<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 17:40
 */

namespace houseorm\Cache\Drivers\MemoryDriver;

use houseorm\Cache\Config\CacheConfigInterface;
use houseorm\Cache\Drivers\CacheDriverInterface;
use houseorm\Cache\Drivers\MemoryDriver\Registry\Registry;
use houseorm\Cache\Drivers\MemoryDriver\Registry\RegistryInterface;
use houseorm\Cache\Request\Find\FindCacheRequestInterface;
use houseorm\Cache\Request\Reset\ResetCacheRequestInterface;
use houseorm\Cache\Request\Set\SetCacheRequestInterface;
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
     * @param CacheConfigInterface $config
     */
    public function __construct(CacheConfigInterface $config)
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
        $pk = $request->getPrimaryKey();
        $target = $request->getTarget();
        return $this->registry->getItemByPrimaryKey($target, $pk);
    }

    /**
     * @param SetCacheRequestInterface $setCacheRequest
     * @return void
     */
    public function set(SetCacheRequestInterface $setCacheRequest)
    {
        $this->registry->setItem(
            $setCacheRequest->getTarget(),
            $setCacheRequest->getPrimaryKey(),
            $setCacheRequest->getEntity()
        );
    }

    public function reset(ResetCacheRequestInterface $request)
    {
        $this->registry->resetItem($request->getTarget(), $request->getPrimaryKey());
    }

}
