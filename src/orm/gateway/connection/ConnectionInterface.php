<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:11
 */

namespace houseorm\gateway\connection;

use houseorm\config\ConfigInterface;
use houseorm\gateway\datatable\request\QueryRequestInterface;

/**
 * Interface ConnectionInterface
 * @package houseorm\gateway\connection
 */
interface ConnectionInterface
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

    /**
     * @return ConfigInterface
     */
    public function getConfig();

    /**
     * @param ConfigInterface $config
     * @return void
     */
    public function setConfig(ConfigInterface $config);

}
