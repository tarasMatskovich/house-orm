<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:58
 */

namespace houseorm\gateway\builder;

use houseorm\gateway\datatable\query\delete\DeleteQueryInterface;
use houseorm\gateway\datatable\query\insert\InsertQueryInterface;
use houseorm\gateway\datatable\query\select\SelectQueryInterface;
use houseorm\gateway\datatable\query\update\UpdateQueryInterface;

/**
 * Interface QueryBuilderInterface
 * @package houseorm\gateway\builder
 */
interface QueryBuilderInterface
{

    /**
     * @return SelectQueryInterface
     */
    public function getSelectQuery();

    /**
     * @return InsertQueryInterface
     */
    public function getInsertQuery();

    /**
     * @return UpdateQueryInterface
     */
    public function getUpdateQuery();

    /**
     * @return DeleteQueryInterface
     */
    public function getDeleteQuery();

}
