<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:12
 */

namespace houseorm\mapper\object;

/**
 * Class DomainObject
 * @package houseorm\mapper\object
 */
class DomainObject implements DomainObjectInterface
{

    /**
     * @var array
     */
    private $attributes;

    /**
     * DomainObject constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

}
