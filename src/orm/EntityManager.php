<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:04
 */

namespace houseorm;


use houseorm\Cache\CacheInterface;
use houseorm\config\ConfigInterface;
use houseorm\EventManager\EventManagerInterface;
use houseorm\EventManager\Events\Create\EntityCreated;
use houseorm\EventManager\Events\Delete\EntityDeleted;
use houseorm\EventManager\Events\Find\EntityFound;
use houseorm\EventManager\Events\Update\EntityUpdated;
use houseorm\EventManager\Listeners\Create\CreateEntityListener;
use houseorm\EventManager\Listeners\Delete\DeleteEntityListener;
use houseorm\EventManager\Listeners\Find\FindEntityListener;
use houseorm\EventManager\Listeners\Update\UpdateEntityListener;
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
     * @var EventManagerInterface|null
     */
    private $eventManager;

    /**
     * @var CacheInterface|null
     */
    private $cache;

    /**
     * EntityManager constructor.
     * @param ConfigInterface $config
     * @param EventManagerInterface|null $eventManager
     * @param CacheInterface|null $cache
     */
    public function __construct(ConfigInterface $config, ?EventManagerInterface $eventManager = null, ?CacheInterface $cache= null)
    {
        $this->config = $config;
        $this->eventManager = $eventManager;
        if ($this->eventManager) {
            $this->eventManager->listen(EntityCreated::EVENT_TYPE, new CreateEntityListener());
            $this->eventManager->listen(EntityUpdated::EVENT_TYPE, new UpdateEntityListener());
            $this->eventManager->listen(EntityDeleted::EVENT_TYPE, new DeleteEntityListener());
            $this->eventManager->listen(EntityFound::EVENT_TYPE, new FindEntityListener());
        }
        $this->cache = $cache;
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

    /**
     * @return EventManagerInterface|null
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @return CacheInterface|null
     */
    public function getCache()
    {
        return $this->cache;
    }
}
