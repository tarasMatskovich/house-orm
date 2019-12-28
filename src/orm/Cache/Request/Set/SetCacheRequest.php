<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 26.12.2019
 * Time: 12:34
 */

namespace houseorm\Cache\Request\Set;

/**
 * Class SetCacheRequest
 * @package houseorm\Cache\Request\Set
 */
class SetCacheRequest implements SetCacheRequestInterface
{

    /**
     * @var string
     */
    private $target;

    /**
     * @var mixed
     */
    private $pk;

    /**
     * @var mixed
     */
    private $entity;

    /**
     * @var array
     */
    private $rawFields;

    /**
     * SetCacheRequest constructor.
     * @param $target
     * @param $pk
     * @param $entity
     * @param array $rawFields
     */
    public function __construct($target, $pk, $entity, array $rawFields = [])
    {
        $this->target = $target;
        $this->pk = $pk;
        $this->entity = $entity;
        $this->rawFields = $rawFields;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->pk;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getRawFields()
    {
        return $this->rawFields;
    }
}
