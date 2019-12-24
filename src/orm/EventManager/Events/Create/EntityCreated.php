<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 12:54
 */

namespace houseorm\EventManager\Events\Create;


use houseorm\EventManager\Events\EventInterface;

/**
 * Class EntityCreated
 * @package houseorm\EventManager\Events\Create
 */
class EntityCreated implements EventInterface
{

    /**
     * @var mixed
     */
    private $payload;

    /**
     * EntityCreated constructor.
     * @param $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    const EVENT_TYPE = 'EntityCreated';

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
