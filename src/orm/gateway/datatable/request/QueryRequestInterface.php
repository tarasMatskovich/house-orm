<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 12.12.2019
 * Time: 15:49
 */

namespace houseorm\gateway\datatable\request;

use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Interface QueryRequestInterface
 * @package houseorm\gateway\datatable\request
 */
interface QueryRequestInterface
{

    /**
     * @return QueryInterface
     */
    public function getQuery();

    public function getPrimaryKey();

}
