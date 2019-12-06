<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:01
 */

namespace houseorm\gateway\datatable\query\delete;

use houseorm\gateway\datatable\query\traits\CriteriaQueryTrait;
use houseorm\gateway\datatable\query\traits\FromQueryTrait;
use houseorm\gateway\datatable\query\traits\LimitQueryTrait;
use houseorm\gateway\datatable\query\traits\OffsetQueryTrait;
use houseorm\gateway\datatable\query\traits\OrderQueryTrait;

/**
 * Class DeleteQuery
 * @package houseorm\gateway\datatable\query\delete
 */
class DeleteQuery implements DeleteQueryInterface
{

    use FromQueryTrait, CriteriaQueryTrait, OrderQueryTrait, LimitQueryTrait, OffsetQueryTrait;

    /**
     * @return DeleteQueryInterface
     */
    public function delete()
    {
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $from
     * @return DeleteQueryInterface
     */
    public function from(array $from)
    {
        $this->from = $from;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $criteria
     * @return DeleteQueryInterface
     */
    public function where(array $criteria)
    {
        $this->criteria = $criteria;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $order
     * @return DeleteQueryInterface
     */
    public function order(array $order)
    {
       $this->order = $order;
       $query = clone $this;
       return $query;
    }

    /**
     * @param $limit
     * @return DeleteQueryInterface
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        $query = clone $this;
        return $query;
    }

    /**
     * @param $offset
     * @return DeleteQueryInterface
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        $query = clone $this;
        return $query;
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        $from = $this->getFrom();
        $criteria = $this->getCriteria();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $offset = $this->getOffset();
        $statement = "DELETE FROM {$from} WHERE {$criteria} ";
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
}
