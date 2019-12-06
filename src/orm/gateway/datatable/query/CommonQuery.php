<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 06.12.2019
 * Time: 12:48
 */

namespace houseorm\gateway\datatable\query;

/**
 * Class CommonQuery
 * @package houseorm\gateway\datatable\query
 */
abstract class CommonQuery
{

    /**
     * @var array
     */
    protected $from;

    /**
     * @var array
     */
    protected $criteria;

    /**
     * @var array
     */
    protected $order;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @return string
     */
    protected function getFrom()
    {
        $from = $this->from;
        return (!$from) ? '' : $from[0] ?? '';
    }

    /**
     * @return string
     */
    protected function getCriteria()
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
    protected function getOrder()
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
    protected function getLimit()
    {
        return (string)$this->limit;
    }

    /**
     * @return string
     */
    protected function getOffset()
    {
        return (string)$this->offset;
    }

}
