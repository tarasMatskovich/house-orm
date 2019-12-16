<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 13.12.2019
 * Time: 17:52
 */

namespace houseorm\config;

/**
 * Interface ConfigInterface
 * @package houseorm\config
 */
interface ConfigInterface
{

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function getDriver();

    /**
     * @param $driver
     * @return void
     */
    public function setDriver($driver);

    /**
     * @return string
     */
    public function getHost();

    /**
     * @param $host
     * @return void
     */
    public function setHost($host);

    /**
     * @return string
     */
    public function getDatabase();

    /**
     * @param $database
     * @return void
     */
    public function setDatabase($database);

    /**
     * @return string
     */
    public function getUser();

    /**
     * @param $user
     * @return void
     */
    public function setUser($user);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param $password
     * @return void
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getCharset();

    /**
     * @param $charset
     * @return void
     */
    public function setCharset($charset);

}
