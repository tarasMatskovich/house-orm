<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:07
 */

namespace houseorm\EventManager\Listeners\Find;


use houseorm\EventManager\Listeners\ListenerInterface;

/**
 * Class FindEntityListener
 * @package houseorm\EventManager\Listeners\Find
 */
class FindEntityListener implements ListenerInterface
{

    /**
     * @param $payload
     * @return void
     */
    public function __invoke($payload)
    {
//        echo 'Entity was found' . "\r\n";
    }
}
