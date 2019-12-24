<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:02
 */

namespace houseorm\EventManager\Events\Delete;


use houseorm\EventManager\Events\EventInterface;

/**
 * Class EntityDeleted
 * @package houseorm\EventManager\Events\Delete
 */
class EntityDeleted implements EventInterface
{

    const EVENT_TYPE = 'EntityDeleted';

    /**
     * @var mixed
     */
    private $payload;

    /**
     * EntityDeleted constructor.
     * @param $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return static::EVENT_TYPE;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
