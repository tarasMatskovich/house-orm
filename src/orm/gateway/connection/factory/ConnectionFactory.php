<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 18.12.2019
 * Time: 11:07
 */

namespace houseorm\gateway\connection\factory;


use houseorm\config\Config;
use houseorm\gateway\connection\ConnectionInterface;
use houseorm\gateway\connection\InMemoryConnection;
use houseorm\gateway\connection\PdoConnection;


/**
 * Class ConnectionFactory
 * @package houseorm\gateway\connection\factory
 */
class ConnectionFactory implements ConnectionFactoryInterface
{

    /**
     * @var ConnectionInterface
     */
    private $memoryConnection;

    /**
     * @var ConnectionInterface
     */
    private $pdoConnection;

    /**
     * ConnectionFactory constructor.
     */
    public function __construct()
    {
        $this->memoryConnection = new InMemoryConnection();
        $this->pdoConnection = new PdoConnection();
    }

    /**
     * @param $driver
     * @return ConnectionInterface
     */
    public function getConnection($driver): ConnectionInterface
    {
        switch($driver) {
            case Config::DRIVER_MEMORY:
                return $this->memoryConnection;
            case Config::DRIVER_MYSQL:
                return $this->pdoConnection;
            case Config::DRIVER_PG_SQL:
                return $this->pdoConnection;
            default:
                return $this->memoryConnection;
        }
    }
}
