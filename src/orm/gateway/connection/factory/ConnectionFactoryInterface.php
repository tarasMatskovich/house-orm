<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 18.12.2019
 * Time: 11:06
 */

namespace houseorm\gateway\connection\factory;

use houseorm\gateway\connection\ConnectionInterface;

/**
 * Interface ConnectionFactoryInterface
 * @package houseorm\gateway\connection\factory
 */
interface ConnectionFactoryInterface
{

    /**
     * @param $driver
     * @return ConnectionInterface
     */
    public function getConnection($driver): ConnectionInterface;

}
