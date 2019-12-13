<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:12
 */

namespace houseorm\gateway\datatable\query\update;


use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Interface UpdateQueryInterface
 * @package houseorm\gateway\datatable\query\update
 */
interface UpdateQueryInterface extends QueryInterface
{

    /**
     * @param array $update
     * @return UpdateQueryInterface
     */
    public function update(array $update);

    /**
     * @param array $set
     * @return UpdateQueryInterface
     */
    public function set(array $set);

    /**
     * @param array $criteria
     * @return UpdateQueryInterface
     */
    public function where(array $criteria);

    /**
     * @param $limit
     * @return UpdateQueryInterface
     */
    public function limit($limit);

    /**
     * @param $offset
     * @return UpdateQueryInterface
     */
    public function offset($offset);

    /**
     * @return array
     */
    public function getUpdatePart();

    /**
     * @return array
     */
    public function getSetPart();

    /**
     * @return array
     */
    public function getWherePart();

}
