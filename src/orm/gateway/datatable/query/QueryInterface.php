<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 17:00
 */

namespace houseorm\gateway\datatable\query;

/**
 * Interface QueryInterface
 * @package houseorm\gateway
 */
interface QueryInterface
{

    /**
     * @return string
     */
    public function getStatement();

    /**
     * @return string
     */
    public function getPreparedStatement();

}
