<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 10:53
 */

namespace houseorm\gateway\builder;


use houseorm\gateway\datatable\query\delete\DeleteQuery;
use houseorm\gateway\datatable\query\delete\DeleteQueryInterface;
use houseorm\gateway\datatable\query\insert\InsertQuery;
use houseorm\gateway\datatable\query\insert\InsertQueryInterface;
use houseorm\gateway\datatable\query\select\SelectQuery;
use houseorm\gateway\datatable\query\select\SelectQueryInterface;
use houseorm\gateway\datatable\query\update\UpdateQuery;
use houseorm\gateway\datatable\query\update\UpdateQueryInterface;

class QueryBuilder implements QueryBuilderInterface
{

    /**
     * @return SelectQueryInterface
     */
    public function getSelectQuery()
    {
        return new SelectQuery();
    }

    /**
     * @return InsertQueryInterface
     */
    public function getInsertQuery()
    {
        return new InsertQuery();
    }

    /**
     * @return UpdateQueryInterface
     */
    public function getUpdateQuery()
    {
        return new UpdateQuery();
    }

    /**
     * @return DeleteQueryInterface
     */
    public function getDeleteQuery()
    {
        return new DeleteQuery();
    }
}
