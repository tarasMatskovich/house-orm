<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:07
 */

namespace houseorm\gateway\datatable\query\insert;

/**
 * Class InsertQuery
 * @package houseorm\gateway\datatable\query\insert
 */
class InsertQuery implements InsertQueryInterface
{

    /**
     * @var array
     */
    private $into;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var array
     */
    private $values;

    /**
     * @param array $into
     * @return InsertQueryInterface
     */
    public function into(array $into)
    {
        $this->into = $into;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $fields
     * @return InsertQueryInterface
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;
        $query = clone $this;
        return $query;
    }

    /**
     * @param array $values
     * @return InsertQueryInterface
     */
    public function values(array $values)
    {
        $this->values = $values;
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
