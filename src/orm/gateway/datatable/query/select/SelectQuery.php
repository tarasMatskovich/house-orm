<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 17:01
 */

namespace houseorm\gateway\datatable\query\select;

use houseorm\gateway\datatable\query\traits\CriteriaQueryTrait;
use houseorm\gateway\datatable\query\traits\FromQueryTrait;
use houseorm\gateway\datatable\query\traits\LimitQueryTrait;
use houseorm\gateway\datatable\query\traits\OffsetQueryTrait;
use houseorm\gateway\datatable\query\traits\OrderQueryTrait;

/**
 * Class SelectQuery
 * @package houseorm\gateway\datatable\query\select
 */
class SelectQuery implements SelectQueryInterface
{

    use FromQueryTrait, CriteriaQueryTrait, OrderQueryTrait, LimitQueryTrait, OffsetQueryTrait;

    /**
     * @var array
     */
    private $select;

    /**
     * @return string
     */
    public function getStatement()
    {
        $select = $this->getSelectFields();
        $from = $this->getFrom();
        $criteria = $this->getCriteria();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $offset = $this->getOffset();
        $statement = "SELECT {$select} FROM {$from} ";
        if ($criteria) {
            $statement .= "WHERE {$criteria} ";
        }
        if ($order) {
            $statement .= "ORDER BY {$order} ";
        }
        if ($limit) {
            $statement .= "LIMIT {$limit} ";
        }
        if ($offset) {
            $statement .= "OFFSET {$offset}";
        }
        return $statement;
    }

    /**
     * @return string
     */
    public function getPreparedStatement()
    {
        $select = $this->getSelectFields();
        $from = $this->getFrom();
        $criteria = $this->getPreparedCriteria();
        $order = $this->getPreparedOrder();
        $limit = $this->getPreparedLimit();
        $offset = $this->getPreparedOffset();
        $statement = "SELECT {$select} FROM {$from} ";
        if ($criteria) {
            $statement .= "WHERE {$criteria} ";
        }
        if ($order) {
            $statement .= "ORDER BY {$order} ";
        }
        if ($limit) {
            $statement .= "LIMIT {$limit} ";
        }
        if ($offset) {
            $statement .= "OFFSET {$offset}";
        }
        return $statement;
    }

    /**
     * @return string
     */
    private function getSelectFields()
    {
        $fields = $this->select;
        return (!$fields || empty($fields)) ? '*' : implode(',', $fields);
    }

    /**
     * @param array $select
     * @return SelectQueryInterface
     */
    public function select(array $select)
    {
        $this->select = $select;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $from
     * @return SelectQueryInterface
     */
    public function from(array $from)
    {
        $this->from = $from;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $criteria
     * @return SelectQueryInterface
     */
    public function where(array $criteria)
    {
        $this->criteria = $criteria;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $order
     * @return SelectQueryInterface
     */
    public function order(array $order)
    {
        $this->order = $order;
        $query = clone $this;
        return $query;
    }

    /**
     * @param int $limit
     * @return SelectQueryInterface
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        $query = clone $this;
        return $query;
    }

    /**
     * @param int $offset
     * @return SelectQueryInterface
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        $query = clone $this;
        return $query;
    }
}
