<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 12.12.2019
 * Time: 15:50
 */

namespace houseorm\gateway\datatable\request;


use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Class QueryRequest
 * @package houseorm\gateway\datatable\request
 */
class QueryRequest implements QueryRequestInterface
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
     * QueryRequest constructor.
     * @param QueryInterface $query
     * @param string $pk
     */
    public function __construct(QueryInterface $query, string $pk = 'id')
    {
        $this->query = $query;
        $this->pk = $pk;
    }

    /**
     * @return QueryInterface
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->pk;
    }
}
