<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:44
 */

namespace tests\orm\EventManager\Listeners;


use houseorm\EventManager\Listeners\ListenerInterface;

/**
 * Class CustomListener
 * @package tests\EventManager\Listeners
 */
class CustomListener implements ListenerInterface
{

    /**
     * @param $payload
     * @return void
     */
    public function __invoke($payload)
    {
        echo "This is a custom listener\r\n";
    }
}
