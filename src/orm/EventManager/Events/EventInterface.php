<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 12:37
 */

namespace houseorm\EventManager\Events;

/**
 * Interface EventInterface
 * @package houseorm\EventManager\Events
 */
interface EventInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @return mixed
     */
    public function getPayload();

}
