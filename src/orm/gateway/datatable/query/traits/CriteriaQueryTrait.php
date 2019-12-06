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

}
