<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 06.12.2019
 * Time: 17:54
 */

namespace houseorm\gateway\datatable\query\traits;

/**
 * Class LimitQueryTrait
 * @package houseorm\gateway\datatable\query\traits
 */
trait LimitQueryTrait
{

    /**
     * @var int
     */
    protected $limit;

    /**
     * @return string
     */
    protected function getLimit()
    {
        return (string)$this->limit;
    }

    /**
     * @return string|null
     */
    protected function getPreparedLimit()
    {
        return ($this->limit) ? BindingsEnum::LIMIT_BINDING : null;
    }

}
