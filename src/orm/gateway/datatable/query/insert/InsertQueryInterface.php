<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 03.12.2019
 * Time: 11:05
 */

namespace houseorm\gateway\datatable\query\insert;


use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Interface InsertQueryInterface
 * @package houseorm\gateway\datatable\query\insert
 */
interface InsertQueryInterface extends QueryInterface
{

    /**
     * @return InsertQueryInterface
     */
    public function insert();

    /**
     * @param array $into
     * @return InsertQueryInterface
     */
    public function into(array $into);

    /**
     * @param array $fields
     * @return InsertQueryInterface
     */
    public function fields(array $fields);

    /**
     * @return array
     */
    public function getIntoPart();

    /**
     * @return array
     */
    public function getFieldsPart();

}
