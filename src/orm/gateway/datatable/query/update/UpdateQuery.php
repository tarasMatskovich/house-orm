<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:15
 */

namespace houseorm\gateway\datatable\query\update;

use houseorm\gateway\datatable\query\traits\BindingsEnum;
use houseorm\gateway\datatable\query\traits\CriteriaQueryTrait;
use houseorm\gateway\datatable\query\traits\LimitQueryTrait;
use houseorm\gateway\datatable\query\traits\OffsetQueryTrait;

/**
 * Class UpdateQuery
 * @package houseorm\gateway\datatable\query\update
 */
class UpdateQuery implements UpdateQueryInterface
{

    use CriteriaQueryTrait, LimitQueryTrait, OffsetQueryTrait;

    /**
     * @var array
     */
    private $update;

    /**
     * @var array
     */
    private $set;

    /**
     * @return string
     */
    private function getUpdate()
    {
        $updateFields = '';
        $update = $this->update;
        foreach ($update as $field) {
            $updateFields .= $field . ',';
        }
        return ($updateFields !== '') ? substr($updateFields, 0, -1) : '';
    }

    /**
     * @return string
     */
    private function getSet()
    {
        $setFields = '';
        $set = $this->set;
        foreach ($set as $field => $value) {
            $setFields .= "{$field}={$value},";
        }
        return ($setFields !== '') ? substr($setFields, 0, -1) : '';
    }

    /**
     * @return string
     */
    private function getPreparedSet()
    {
        $setFields = '';
        $set = $this->set;
        $criteriaBinding = BindingsEnum::CRITERIA_BINDING;
        foreach ($set as $field => $value) {
            $setFields .= "{$field}={$criteriaBinding}{$field},";
        }
        return ($setFields !== '') ? substr($setFields, 0, -1) : '';
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        $update = $this->getUpdate();
        $set = $this->getSet();
        $criteria = $this->getCriteria();
        $limit = $this->getLimit();
        $offset = $this->getOffset();
        $updateStatement = "UPDATE {$update} SET {$set}";
        if ($criteria) {
            $updateStatement .= " WHERE {$criteria}";
        }
        if ($limit) {
            $updateStatement .= " LIMIT {$limit}";
        }
        if ($offset) {
            $updateStatement .= " OFFSET {$offset}";
        }
        return $updateStatement;
    }

    public function getPreparedStatement()
    {
        $update = $this->getUpdate();
        $set = $this->getPreparedSet();
        $criteria = $this->getPreparedCriteria();
        $limit = $this->getPreparedLimit();
        $offset = $this->getPreparedOffset();
        $updateStatement = "UPDATE {$update} SET {$set}";
        if ($criteria) {
            $updateStatement .= " WHERE {$criteria}";
        }
        if ($limit) {
            $updateStatement .= " LIMIT {$limit}";
        }
        if ($offset) {
            $updateStatement .= " OFFSET {$offset}";
        }
        return $updateStatement;
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
     * @param array $criteria
     * @return UpdateQueryInterface
     */
    public function where(array $criteria)
    {
        $this->criteria = $criteria;
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
