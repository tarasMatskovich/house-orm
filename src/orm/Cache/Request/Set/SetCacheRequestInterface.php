<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 26.12.2019
 * Time: 12:32
 */

namespace houseorm\Cache\Request\Set;

/**
 * Interface SetCacheRequestInterface
 * @package houseorm\Cache\Request\Set
 */
interface SetCacheRequestInterface
{

    /**
     * @return string
     */
    public function getTarget();

    /**
     * @return mixed
     */
    public function getPrimaryKey();

    /**
     * @return mixed
     */
    public function getEntity();

    /**
     * @return array
     */
    public function getRawFields();

}
