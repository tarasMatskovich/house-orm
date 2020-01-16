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
     * @var array
     */
    private $join;

    /**
     * @var SelectQueryInterface[]
     */
    private $unions;

    /**
     * @return string
     */
    public function getStatement()
    {
        $select = $this->getSelectFields();
        $from = $this->getFrom();
        $join = $this->getJoin();
        $criteria = $this->getCriteria();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $offset = $this->getOffset();
        $unions = $this->getUnions();
        $statement = "SELECT {$select} FROM {$from} ";
        if ($join) {
            $statement .= $join . " ";
        }
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
            $statement .= "OFFSET {$offset} ";
        }
        if ($unions) {
            $statement .= $unions;
        }
        return rtrim($statement);;
    }

    /**
     * @return string
     */
    public function getPreparedStatement()
    {
        $select = $this->getSelectFields();
        $from = $this->getFrom();
        $join = $this->getJoin();
        $criteria = $this->getPreparedCriteria();
        $order = $this->getPreparedOrder();
        $limit = $this->getPreparedLimit();
        $offset = $this->getPreparedOffset();
        $unions = $this->getPreparedUnions();
        $statement = "SELECT {$select} FROM {$from} ";
        if ($join) {
            $statement .= $join . " ";
        }
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
            $statement .= "OFFSET {$offset} ";
        }
        if ($unions) {
            $statement .= $unions;
        }
        return rtrim($statement);
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

    public function getFromPart()
    {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getWherePart()
    {
        return $this->criteria;
    }

    /**
     * @param array $join
     * @param string $type
     * @return SelectQueryInterface
     */
    public function join(array $join, string $type = '')
    {
        $this->join[] = [
            'type' => $type,
            'join' => $join
        ];
        $query = clone $this;
        return $query;
    }

    /**
     * @return string|null
     */
    private function getJoin()
    {
        if (!$this->join) {
            return null;
        }
        $result = '';
        foreach ($this->join as $joinArgs) {
            $joinStatement = '';
            $join = $joinArgs['join'];
            $joinType = $joinArgs['type'];
            foreach ($join as $joinTable => $joinOn) {
                $joinStatement .= $joinType . ' JOIN '. $joinTable . ' ON ' . $joinOn . ' ';
            }
            $result .= $joinStatement;
        }
        return rtrim($result);
    }

    /**
     * @param SelectQueryInterface $query
     * @return SelectQueryInterface
     */
    public function union(SelectQueryInterface $query)
    {
        $this->unions[] = $query;
        $query = clone $this;
        return $query;
    }

    /**
     * @return string|null
     */
    private function getUnions()
    {
        $unions = $this->unions;
        if (!$unions) {
            return null;
        }
        $unionStatement = '';
        foreach ($unions as $union) {
            $unionQueryStatement = $union->getStatement();
            $unionStatement .= 'UNION ' . $unionQueryStatement . ' ';
        }
        return $unionStatement;
    }

    /**
     * @return string|null
     */
    private function getPreparedUnions()
    {
        $unions = $this->unions;
        if (!$unions) {
            return null;
        }
        $unionStatement = '';
        foreach ($unions as $union) {
            $unionQueryStatement = $union->getPreparedStatement();
            $unionStatement .= 'UNION ' . $unionQueryStatement . ' ';
        }
        return $unionStatement;
    }
}
