<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 14:57
 */

namespace houseorm\EventManager\Listeners\Create;


use houseorm\EventManager\Listeners\ListenerInterface;

/**
 * Class CreateEntityListener
 * @package houseorm\EventManager\Listeners\Create
 */
class CreateEntityListener implements ListenerInterface
{

    /**
     * @param $payload
     * @return void
     */
    public function __invoke($payload)
    {
        echo 'Entity was created' . "\r\n";
    }
}
