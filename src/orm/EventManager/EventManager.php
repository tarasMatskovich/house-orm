<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 12:39
 */

namespace houseorm\EventManager;


use houseorm\EventManager\Events\EventInterface;
use Psr\Log\LoggerInterface;

/**
 * Class EventManager
 * @package houseorm\EventManager
 */
class EventManager implements EventManagerInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $listeners = [];

    /**
     * EventManager constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $event
     * @param callable $callback
     * @return void
     */
    public function listen(string $event, callable $callback)
    {
        $this->listeners[$event] = $callback;
        $this->logger->debug('Start listening event: ' . $event);
    }

    /**
     * @param string $eventType
     * @param EventInterface $event
     * @return void
     */
    public function dispatch(string $eventType, EventInterface $event)
    {
        if (isset($this->listeners[$eventType])) {
            $this->listeners[$eventType]($event);
            $this->logger->debug('Dispathed event: ' . $eventType);
        }
    }
}
