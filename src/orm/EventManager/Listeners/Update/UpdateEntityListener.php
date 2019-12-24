<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:08
 */

namespace houseorm\EventManager\Listeners\Update;


use houseorm\EventManager\Listeners\ListenerInterface;

/**
 * Class UpdateEntityListener
 * @package houseorm\EventManager\Listeners\Update
 */
class UpdateEntityListener implements ListenerInterface
{

    /**
     * @param $payload
     * @return void
     */
    public function __invoke($payload)
    {
        echo 'Entity was updated' . "\r\n";
    }
}
