<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:59
 */

namespace houseorm\gateway\datatable\query\select;

use houseorm\gateway\datatable\query\QueryInterface;

/**
 * Interface SelectQueryInterface
 * @package houseorm\gateway\datatable\query\select
 */
interface SelectQueryInterface extends QueryInterface
{

    /**
     * @param array $select
     * @return SelectQueryInterface
     */
    public function select(array $select);

    /**
     * @param array $from
     * @return SelectQueryInterface
     */
    public function from(array $from);

    /**
     * @param array $criteria
     * @return SelectQueryInterface
     */
    public function where(array $criteria);

    /**
     * @param array $order
     * @return SelectQueryInterface
     */
    public function order(array $order);

    /**
     * @param int $limit
     * @return SelectQueryInterface
     */
    public function limit($limit);

    /**
     * @param int $offset
     * @return SelectQueryInterface
     */
    public function offset($offset);

    /**
     * @return array
     */
    public function getFromPart();

    /**
     * @return array
     */
    public function getWherePart();

    /**
     * @param array $join
     * @param string $type
     * @return SelectQueryInterface
     */
    public function join(array $join, string $type = '');

    /**
     * @param SelectQueryInterface $query
     * @return SelectQueryInterface
     */
    public function union(SelectQueryInterface $query);

}
