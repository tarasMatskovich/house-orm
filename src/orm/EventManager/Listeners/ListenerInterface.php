<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 14:56
 */

namespace houseorm\EventManager\Listeners;

/**
 * Interface ListenerInterface
 * @package houseorm\EventManager\Listeners
 */
interface ListenerInterface
{

    /**
     * @param $payload
     * @return void
     */
    public function __invoke($payload);

}
