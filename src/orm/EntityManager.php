<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:04
 */

namespace houseorm;


use houseorm\Cache\Cache;
use houseorm\Cache\CacheInterface;
use houseorm\config\ConfigInterface;
use houseorm\EventManager\EventManager;
use houseorm\EventManager\EventManagerInterface;
use houseorm\EventManager\Events\Create\EntityCreated;
use houseorm\EventManager\Events\Delete\EntityDeleted;
use houseorm\EventManager\Events\Find\EntityFound;
use houseorm\EventManager\Events\Update\EntityUpdated;
use houseorm\EventManager\Listeners\Create\CreateEntityListener;
use houseorm\EventManager\Listeners\Delete\DeleteEntityListener;
use houseorm\EventManager\Listeners\Find\FindEntityListener;
use houseorm\EventManager\Listeners\Update\UpdateEntityListener;
use houseorm\gateway\connection\factory\ConnectionFactory;
use houseorm\gateway\connection\factory\ConnectionFactoryInterface;
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
     * @var ConnectionFactoryInterface
     */
    private $connectionFactory;

    /**
     * EntityManager constructor.
     * @param ConfigInterface $config
     * @param EventManagerInterface|null $eventManager
     */
    public function __construct(ConfigInterface $config, ?EventManagerInterface $eventManager = null)
    {
        $this->config = $config;
        $this->eventManager = $eventManager;
        if (!$this->eventManager) {
            $this->eventManager = new EventManager();
        }
        $cacheConfig = $config->getCacheConfig();
        if ($cacheConfig) {
            $this->cache = new Cache($cacheConfig);
        }
        $this->eventManager->listen(EntityCreated::EVENT_TYPE, new CreateEntityListener());
        $this->eventManager->listen(EntityUpdated::EVENT_TYPE, new UpdateEntityListener($this->cache));
        $this->eventManager->listen(EntityDeleted::EVENT_TYPE, new DeleteEntityListener());
        $this->eventManager->listen(EntityFound::EVENT_TYPE, new FindEntityListener());
        $this->connectionFactory = new ConnectionFactory();
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

    /**
     * @return ConnectionFactoryInterface
     */
    public function getConnectionFactory()
    {
        return $this->connectionFactory;
    }
}
