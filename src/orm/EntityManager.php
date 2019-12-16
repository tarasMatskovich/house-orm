<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:04
 */

namespace houseorm;


use houseorm\config\ConfigInterface;
use houseorm\mapper\DomainMapperInterface;

/**
 * Class EntityManager
 * @package houseorm
 */
class EntityManager implements EntityManagerInterface
{

    /**
     * @var DomainMapperInterface[]
     */
    private $mappers;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param string $mapper
     * @return DomainMapperInterface
     * @throws EntityManagerException
     */
    public function getMapper(string $mapper)
    {
        if (!isset($this->mappers[$mapper])) {
            throw new EntityManagerException("Mapper {$mapper}  was not found");
        }
        return $this->mappers[$mapper];
    }

    /**
     * @param string $key
     * @param DomainMapperInterface $mapper
     * @return void
     */
    public function setMapper(string $key, DomainMapperInterface $mapper)
    {
        $mapper->setEntityManager($this);
        $this->mappers[$key] = $mapper;
    }

    /**
     * @param ConfigInterface $config
     */
    public function setDefaultConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return ConfigInterface
     */
    public function getDefaultConfig()
    {
        return $this->config;
    }
}
