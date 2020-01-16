<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 06.12.2019
 * Time: 17:53
 */

namespace houseorm\gateway\datatable\query\traits;

/**
 * Trait CriteriaQueryTrait
 * @package houseorm\gateway\datatable\query\traits
 */
trait CriteriaQueryTrait
{

    /**
     * @var array
     */
    protected $criteria;

    /**
     * @return string|null
     */
    protected function getCriteria()
    {
        $criteria = $this->criteria;
        if (!$criteria) {
            return null;
        }
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
     * @return string|null
     */
    protected function getPreparedCriteria()
    {
        $criteria = $this->criteria;
        if (!$criteria) {
            return null;
        }
        $criteriaStatement = '';
        foreach ($criteria as $key => $value) {
            $operator = '=';
            $field = $key;
            $val = ':' . $field;
            if (\is_array($value)) {
                $operator = $value[1] ?? '=';
                $field = $value[0] ?? '';
                $val = ':' . $field;
            }
            if ('' === $criteriaStatement) {
                $criteriaStatement = "{$field} {$operator} {$val} ";
            } else {
                $criteriaStatement .= "AND {$field} {$operator} {$val} ";
            }
        }
        return substr($criteriaStatement, 0, -1);
    }

}
