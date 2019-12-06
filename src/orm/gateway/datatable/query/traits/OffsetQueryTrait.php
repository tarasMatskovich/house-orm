<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 06.12.2019
 * Time: 17:54
 */

namespace houseorm\gateway\datatable\query\traits;

/**
 * Trait OffsetQueryTrait
 * @package houseorm\gateway\datatable\query\traits
 */
trait OffsetQueryTrait
{

    /**
     * @var int
     */
    protected $offset;

    /**
     * @return string
     */
    protected function getOffset()
    {
        return (string)$this->offset;
    }

}
