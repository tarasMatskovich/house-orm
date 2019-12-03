<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:01
 */

namespace houseorm\gateway\datatable\query\delete;

/**
 * Class DeleteQuery
 * @package houseorm\gateway\datatable\query\delete
 */
class DeleteQuery implements DeleteQueryInterface
{

    /**
     * @var array
     */
    private $from;

    /**
     * @var array
     */
    private $criteria;

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
     * @return string
     */
    public function getStatement()
    {
        return '';
    }
}
