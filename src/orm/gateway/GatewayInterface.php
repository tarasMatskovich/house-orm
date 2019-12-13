<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:39
 */

namespace houseorm\gateway;

use houseorm\gateway\datatable\request\QueryRequestInterface;

/**
 * Interface GatewayInterface
 * @package houseorm\gateway
 */
interface GatewayInterface
{

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    public function execute(QueryRequestInterface $queryRequest);

    /**
     * @return int|null
     */
    public function getLastInsertId();

}
