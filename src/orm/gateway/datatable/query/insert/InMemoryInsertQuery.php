<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 12.12.2019
 * Time: 18:43
 */

namespace houseorm\gateway\datatable\query\insert;


use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Class InMemoryInsertQuery
 * @package houseorm\gateway\datatable\query\insert
 */
class InMemoryInsertQuery implements InsertQueryInterface
{

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var string
     */
    private $pk;

    /**
     * InMemoryInsertQuery constructor.
     * @param InsertQueryInterface $query
     * @param string $pk
     */
    public function __construct(
        InsertQueryInterface $query,
        $pk = 'id'
    )
    {
        $this->query = $query;
        $this->pk = $pk;
    }

    /**
     * @return InsertQueryInterface
     */
    public function insert()
    {
        return $this->query->insert();
    }

    /**
     * @param array $into
     * @return InsertQueryInterface
     */
    public function into(array $into)
    {
        return $this->query->into($into);
    }

    /**
     * @param array $fields
     * @return InsertQueryInterface
     */
    public function fields(array $fields)
    {
        return $this->query->fields($fields);
    }


    /**
     * @return array
     */
    public function getIntoPart()
    {
        return $this->query->getIntoPart();
    }

    /**
     * @return array
     */
    public function getFieldsPart()
    {
        return $this->query->getFieldsPart();
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        return $this->query->getStatement();
    }

    /**
     * @return string
     */
    public function getPreparedStatement()
    {
        return $this->query->getStatement();
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->pk;
    }
}
