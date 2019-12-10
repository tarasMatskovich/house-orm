<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 06.12.2019
 * Time: 17:54
 */

namespace houseorm\gateway\datatable\query\traits;

/**
 * Trait OrderQueryTrait
 * @package houseorm\gateway\datatable\query\traits
 */
trait OrderQueryTrait
{
    /**
     * @var array
     */
    protected $order;

    /**
     * @return string|null
     * TODO add multiple order
     */
    protected function getOrder()
    {
        $order = $this->order;
        if (null === $order || empty($order)) {
            return null;
        }
        foreach ($order as $by => $type) {
            $innerType = null;
            $sortBy = null;
            switch ($type) {
                case 'DESC':
                    $innerType = 'DESC';
                    break;
                case 'ASC':
                    $innerType = 'ASC';
                    break;
                default:
                    $innerType = 'ASC';
                    break;
            }
            $sortBy = $by;
            $orderStatement = "{$sortBy} {$innerType}";
            return $orderStatement;
        }
    }

    /**
     * @return string|null
     */
    protected function getPreparedOrder()
    {
        $order = $this->order;
        if (null === $order || empty($order)) {
            return null;
        }
        foreach ($order as $by => $type) {
            $innerType = null;
            $sortBy = null;
            switch ($type) {
                case 'DESC':
                    $innerType = 'DESC';
                    break;
                case 'ASC':
                    $innerType = 'ASC';
                    break;
                default:
                    $innerType = 'ASC';
                    break;
            }
            $sortBy = BindingsEnum::ORDER_BINDING;
            $orderStatement = "{$sortBy} {$innerType}";
            return $orderStatement;
        }
    }
}
