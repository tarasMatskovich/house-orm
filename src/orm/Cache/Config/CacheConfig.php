<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 26.12.2019
 * Time: 16:37
 */

namespace houseorm\Cache\Config;

/**
 * Class CacheConfig
 * @package houseorm\Cache\Config
 */
class CacheConfig implements CacheConfigInterface
{

    const MEMORY_DRIVER = 'memory';

    const DEFAULT_LIFETIME = 1;

    /**
     * @var string
     */
    private $driver;

    /**
     * @var int
     */
    private $lifetime;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * CacheConfig constructor.
     * @param $driver
     * @param int $lifetime
     * @param null $host
     * @param null $user
     * @param null $password
     */
    public function __construct($driver, int $lifetime = self::DEFAULT_LIFETIME, $host = null, $user = null, $password = null)
    {
        $this->driver = $driver;
        $this->lifetime = $lifetime;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return int
     */
    public function getLifeTime()
    {
        return $this->lifetime;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
