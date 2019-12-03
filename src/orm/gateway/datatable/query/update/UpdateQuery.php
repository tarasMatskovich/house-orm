<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:15
 */

namespace houseorm\gateway\datatable\query\update;

/**
 * Class UpdateQuery
 * @package houseorm\gateway\datatable\query\update
 */
class UpdateQuery implements UpdateQueryInterface
{

    /**
     * @var array
     */
    private $update;

    /**
     * @var array
     */
    private $set;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @return string
     */
    public function getStatement()
    {
        return '';
    }

    /**
     * @param array $update
     * @return UpdateQueryInterface
     */
    public function update(array $update)
    {
        $this->update = $update;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $set
     * @return UpdateQueryInterface
     */
    public function set(array $set)
    {
        $this->set = $set;
        $query = clone $this;
        return $query;
    }

    /**
     * @param $limit
     * @return UpdateQueryInterface
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        $query = clone $this;
        return $query;
    }

    /**
     * @param $offset
     * @return UpdateQueryInterface
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        $query = clone $this;
        return $query;
    }
}
