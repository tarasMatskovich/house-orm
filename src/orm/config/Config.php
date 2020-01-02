<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 13.12.2019
 * Time: 17:56
 */

namespace houseorm\config;

use houseorm\Cache\Config\CacheConfigInterface;

/**
 * Class Config
 * @package houseorm\config
 */
class Config implements ConfigInterface
{

    const DRIVER_MYSQL = 'mysql';

    const DRIVER_MEMORY = 'memory';

    const AVAILABLE_DRIVERS = [
        self::DRIVER_MYSQL,
        self::DRIVER_MEMORY
    ];

    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $charset;

    /**
     * @var CacheConfigInterface|null
     */
    private $cache;

    /**
     * Config constructor.
     * @param array $attributes
     * @throws ConfigException
     */
    public function __construct(array $attributes)
    {
        $this->parseAttributes($attributes);
    }

    /**
     * @param array $attributes
     * @throws ConfigException
     */
    private function parseAttributes(array $attributes)
    {
        if (isset($attributes['driver'])) {
            if (!\in_array($attributes['driver'], self::AVAILABLE_DRIVERS)) {
                throw new ConfigException('Config error: unknown driver - ' . $attributes['driver']);
            }
            $this->driver = $attributes['driver'];
        }
        if (isset($attributes['host'])) {
            $this->host = $attributes['host'];
        }
        if (isset($attributes['database'])) {
            $this->database = $attributes['database'];
        }
        if (isset($attributes['user'])) {
            $this->user = $attributes['user'];
        }
        if (isset($attributes['password'])) {
            $this->password = $attributes['password'];
        }
        if (isset($attributes['charset'])) {
            $this->charset = $attributes['charset'];
        }
        if (isset($attributes['cache']) && $attributes['cache'] instanceof CacheConfigInterface) {
            $this->cache = $attributes['cache'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'driver' => $this->getDriver(),
            'host' => $this->getHost(),
            'database' => $this->getDatabase(),
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'charset' => $this->getCharset()
        ];
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param $driver
     * @return void
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param $database
     * @return void
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return void
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @return string|null
     */
    public function getCacheConfig()
    {
        return $this->cache;
    }

    /**
     * @param CacheConfigInterface $cacheConfig
     */
    public function setCacheConfig(CacheConfigInterface $cacheConfig)
    {
        $this->cache = $cacheConfig;
    }
}
