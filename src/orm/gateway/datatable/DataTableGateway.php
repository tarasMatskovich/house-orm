<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:40
 */

namespace houseorm\gateway\datatable;


use houseorm\gateway\connection\ConnectionInterface;
use houseorm\gateway\datatable\request\QueryRequestInterface;
use houseorm\gateway\GatewayInterface;

/**
 * Class DataTableGateway
 * @package houseorm\gateway\datatable
 */
class DataTableGateway implements GatewayInterface
{

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * DataTableGateway constructor.
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param QueryRequestInterface $queryRequest
     * @return array
     */
    public function execute(QueryRequestInterface $queryRequest)
    {
        return $this->connection->execute($queryRequest);
    }

    /**
     * @return int|null
     */
    public function getLastInsertId()
    {
        return $this->connection->getLastInsertId();
    }
}
