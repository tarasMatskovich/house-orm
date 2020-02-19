<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:08
 */

namespace houseorm\EventManager\Listeners\Update;


use houseorm\Cache\CacheInterface;
use houseorm\Cache\Request\Reset\ResetCacheRequest;
use houseorm\EventManager\Events\EventInterface;
use houseorm\EventManager\Listeners\ListenerInterface;

/**
 * Class UpdateEntityListener
 * @package houseorm\EventManager\Listeners\Update
 */
class UpdateEntityListener implements ListenerInterface
{

    /**
     * @var CacheInterface|null
     */
    private $cache;

    /**
     * UpdateEntityListener constructor.
     * @param CacheInterface|null $cache
     */
    public function __construct(?CacheInterface $cache = null)
    {
        $this->cache = $cache;
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        $entity = $event->getPayload();
        if ($this->cache) {
            $this->cache->reset(new ResetCacheRequest($entity->target, $entity->pk));
        }
//        echo 'Entity was updated' . "\r\n";
    }
}
