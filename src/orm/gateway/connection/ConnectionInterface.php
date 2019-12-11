<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:11
 */

namespace houseorm\gateway\connection;

use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Interface ConnectionInterface
 * @package houseorm\gateway\connection
 */
interface ConnectionInterface
{

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function execute(QueryInterface $query);

}
