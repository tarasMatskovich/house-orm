<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 15:03
 */

namespace houseorm\EventManager\Events\Find;


use houseorm\EventManager\Events\EventInterface;

/**
 * Class FoundEntity
 * @package houseorm\EventManager\Events\Find
 */
class EntityFound implements EventInterface
{

    const EVENT_TYPE = 'FoundEntity';

    /**
     * @var mixed
     */
    private $payload;

    /**
     * FoundEntity constructor.
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
