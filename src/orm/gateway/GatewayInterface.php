<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:39
 */

namespace houseorm\gateway;

use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Interface GatewayInterface
 * @package houseorm\gateway
 */
interface GatewayInterface
{

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function execute(QueryInterface $query);

}
