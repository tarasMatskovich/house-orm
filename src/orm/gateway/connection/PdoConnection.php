<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 19:18
 */

namespace houseorm\gateway\connection;

use houseorm\gateway\datatable\request\QueryRequestInterface;

/**
 * Class PdoConnection
 * @package houseorm\gateway\connection
 */
class PdoConnection implements ConnectionInterface
{

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    public function execute(QueryRequestInterface $queryRequest)
    {
        // EXECUTE SQL QUERY
        $res = [
            'id' => 10,
            'name' => 'Тарас'
        ];
        return $res;
    }

    /**
     * @return int|null
     */
    public function getLastInsertId()
    {
        // TODO: Implement getLastInsertId() method.
    }
}
