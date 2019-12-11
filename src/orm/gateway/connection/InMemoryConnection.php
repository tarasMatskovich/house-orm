<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 12:09
 */

namespace houseorm\gateway\connection;

use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Class InMemoryConnection
 * @package houseorm\gateway\connection
 */
class InMemoryConnection implements ConnectionInterface
{

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function execute(QueryInterface $query)
    {
        return [];
    }
}
