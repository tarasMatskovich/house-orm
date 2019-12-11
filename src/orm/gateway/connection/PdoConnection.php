<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 19:18
 */

namespace houseorm\gateway\connection;

use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Class PdoConnection
 * @package houseorm\gateway\connection
 */
class PdoConnection implements ConnectionInterface
{

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function execute(QueryInterface $query)
    {
        // EXECUTE SQL QUERY
        $res = [
            'id' => 10,
            'name' => 'Тарас'
        ];
        return $res;
    }
}
