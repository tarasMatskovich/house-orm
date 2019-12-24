<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 12:35
 */

namespace houseorm\EventManager;

use houseorm\EventManager\Events\EventInterface;

/**
 * Interface EventManagerInterface
 * @package houseorm\EventManager
 */
interface EventManagerInterface
{

    /**
     * @param string $event
     * @param callable $callback
     * @return void
     */
    public function listen(string $event, callable $callback);

    /**
     * @param string $eventType
     * @param EventInterface $event
     * @return void
     */
    public function dispatch(string $eventType, EventInterface $event);

}
