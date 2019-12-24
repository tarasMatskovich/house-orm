<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 15:57
 */

namespace houseorm;

use houseorm\config\ConfigInterface;
use houseorm\EventManager\EventManagerInterface;
use houseorm\mapper\DomainMapperInterface;

/**
 * Interface EntityManagerInterface
 * @package houseorm
 */
interface EntityManagerInterface
{

    /**
     * @param string $mapper
     * @return DomainMapperInterface|null
     */
    public function getMapper(string $mapper);

    /**
     * @param string $key
     * @param DomainMapperInterface $mapper
     * @return void
     */
    public function setMapper(string $key, DomainMapperInterface $mapper);

    /**
     * @param ConfigInterface $config
     * @return void
     */
    public function setDefaultConfig(ConfigInterface $config);

    /**
     * @return ConfigInterface
     */
    public function getDefaultConfig();

    /**
     * @return EventManagerInterface|null
     */
    public function getEventManager();

}
