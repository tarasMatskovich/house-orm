<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 13.12.2019
 * Time: 17:56
 */

namespace houseorm\config;

/**
 * Class Config
 * @package houseorm\config
 */
class Config implements ConfigInterface
{

    const DRIVER_MYSQL = 'mysql';

    const AVAILABLE_DRIVERS = [
        self::DRIVER_MYSQL
    ];

    private $driver;

    private $database;

    private $user;

    private $password;

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
        if (isset($attributes['database'])) {
            $this->database = $attributes['database'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        // TODO: Implement getDriver() method.
    }

    /**
     * @param $driver
     * @return void
     */
    public function setDriver($driver)
    {
        // TODO: Implement setDriver() method.
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        // TODO: Implement getDatabase() method.
    }

    /**
     * @param $database
     * @return void
     */
    public function setDatabase($database)
    {
        // TODO: Implement setDatabase() method.
    }

    /**
     * @return string
     */
    public function getUser()
    {
        // TODO: Implement getUser() method.
    }

    /**
     * @param $user
     * @return void
     */
    public function setUser($user)
    {
        // TODO: Implement setUser() method.
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    /**
     * @param $password
     * @return void
     */
    public function setPassword($password)
    {
        // TODO: Implement setPassword() method.
    }
}
