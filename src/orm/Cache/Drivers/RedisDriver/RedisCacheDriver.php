<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 27.12.2019
 * Time: 11:11
 */

namespace houseorm\Cache\Drivers\RedisDriver;


use houseorm\Cache\Config\CacheConfigInterface;
use houseorm\Cache\Drivers\CacheDriverInterface;
use houseorm\Cache\Request\Find\FindCacheRequestInterface;
use houseorm\Cache\Request\Reset\ResetCacheRequestInterface;
use houseorm\Cache\Request\Set\SetCacheRequestInterface;
use Predis\Client;

/**
 * Class RedisCacheDriver
 * @package houseorm\Cache\Drivers\RedisDriver
 */
class RedisCacheDriver implements CacheDriverInterface
{

    const DOMAIN_KEY = 'house_orm';

    /**
     * @var CacheConfigInterface
     */
    private $cacheConfig;

    /**
     * @var Client
     */
    private $redisClient;

    /**
     * RedisCacheDriver constructor.
     * @param CacheConfigInterface $cacheConfig
     */
    public function __construct(CacheConfigInterface $cacheConfig)
    {
        $this->cacheConfig = $cacheConfig;
        $this->redisClient = new Client();
    }

    /**
     * @param FindCacheRequestInterface $request
     * @return mixed
     */
    public function get(FindCacheRequestInterface $request)
    {
        $key = $this->makeKey($request->getTarget(), $request->getPrimaryKey());
        return $this->redisClient->get($key);
    }

    /**
     * @param SetCacheRequestInterface $request
     * @return void
     */
    public function set(SetCacheRequestInterface $request)
    {
        $target = $request->getTarget();
        $pk = $request->getPrimaryKey();
        $encodedFields = json_encode($request->getRawFields());
        $key = $this->makeKey($target, $pk);
        $this->redisClient->set($key, $encodedFields, 'EX', (int)$this->cacheConfig->getLifeTime() * 60);
    }

    /**
     * @param ResetCacheRequestInterface $request
     * @return void
     */
    public function reset(ResetCacheRequestInterface $request)
    {
        $key = $this->makeKey($request->getTarget(), $request->getPrimaryKey());
        $this->redisClient->del([$key]);
    }

    /**
     * @param $target
     * @param $pk
     * @return string
     */
    private function makeKey($target, $pk)
    {
        return static::DOMAIN_KEY . ":" . $target . ":" . $pk;
    }
}
