<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:05
 */

namespace houseorm\EventManager\Events\Update;


use houseorm\EventManager\Events\EventInterface;

/**
 * Class UpdatedEntity
 * @package houseorm\EventManager\Events\Update
 */
class EntityUpdated implements EventInterface
{

    const EVENT_TYPE = 'UpdatedEntity';

    /**
     * @var mixed
     */
    private $payload;

    /**
     * UpdatedEntity constructor.
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
