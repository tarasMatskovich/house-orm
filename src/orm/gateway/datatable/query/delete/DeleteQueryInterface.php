<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 10:59
 */

namespace houseorm\gateway\datatable\query\delete;

use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Interface DeleteQueryInterface
 * @package houseorm\gateway\datatable\query\delete
 */
interface DeleteQueryInterface extends QueryInterface
{

    /**
     * @param array $from
     * @return DeleteQueryInterface
     */
    public function from(array $from);

    /**
     * @param array $criteria
     * @return DeleteQueryInterface
     */
    public function where(array $criteria);

}
