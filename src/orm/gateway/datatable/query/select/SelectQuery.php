<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 17:01
 */

namespace houseorm\gateway\datatable\query\select;

/**
 * Class SelectQuery
 * @package houseorm\gateway\datatable\query\select
 */
class SelectQuery implements SelectQueryInterface
{

    /**
     * @var array
     */
    private $select;

    /**
     * @var array
     */
    private $from;

    /**
     * @var array
     */
    private $criteria;

    /**
     * @var array
     */
    private $order;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * SelectQuery constructor.
     */
    public function __construct()
    {

    }


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
        $statement = "SELECT {$select} FROM {$from} WHERE {$criteria} ";
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
     * @return string
     */
    private function getFrom()
    {
        $from = $this->from;
        return (!$from) ? '' : $from[0] ?? '';
    }

    /**
     * @return string
     */
    private function getCriteria()
    {
        $criteria = $this->criteria;
        $criteriaStatement = '';
        foreach ($criteria as $key => $value) {
            $operator = '=';
            $field = $key;
            $val = $value;
            if (\is_array($value)) {
                $operator = $value[1] ?? '=';
                $field = $value[0] ?? '';
                $val = $value[2] ?? '';
            }
            if ('' === $criteriaStatement) {
                $criteriaStatement = "{$field} {$operator} {$val} ";
            } else {
                $criteriaStatement .= "AND {$field} {$operator} {$val} ";
            }
        }
        return substr($criteriaStatement, 0, -1);
    }

    /**
     * @return string
     */
    private function getOrder()
    {
        $order = $this->order;
        if (null === $order || empty($order)) {
            return null;
        }
        foreach ($order as $by => $type) {
            $innerType = null;
            $sortBy = null;
            switch ($type) {
                case 'DESC':
                    $innerType = 'DESC';
                    $sortBy = $by;
                    break;
                case 'ASC':
                    $innerType = 'ASC';
                    $sortBy = $by;
                    break;
                default:
                    $innerType = 'ASC';
                    $sortBy = $by;
                    break;
            }
            $orderStatement = "{$sortBy} {$innerType}";
            return $orderStatement;
        }
    }

    /**
     * @return string
     */
    private function getLimit()
    {
        return (string)$this->limit;
    }

    /**
     * @return string
     */
    private function getOffset()
    {
        return (string)$this->offset;
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
