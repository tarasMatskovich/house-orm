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
     * EntityManager constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $mapper
     * @return DomainMapperInterface|null
     */
    public function getMapper(string $mapper)
    {
        return $this->mappers[$mapper] ?? null;
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
