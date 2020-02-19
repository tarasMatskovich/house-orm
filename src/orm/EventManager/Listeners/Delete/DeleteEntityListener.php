<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:07
 */

namespace houseorm\EventManager\Listeners\Delete;


use houseorm\EventManager\Listeners\ListenerInterface;

/**
 * Class DeleteEntityListener
 * @package houseorm\EventManager\Listeners\Delete
 */
class DeleteEntityListener implements ListenerInterface
{

    /**
     * @param $payload
     * @return void
     */
    public function __invoke($payload)
    {
//        echo 'Entity was deleted' . "\r\n";
    }
}
