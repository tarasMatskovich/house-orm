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
     * @return string
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
                    $sortBy = $by;
                    break;
                case 'ASC':
                    $innerType = 'ASC';
                    $sortBy = $by;
                    break;
                default:
                    $innerType = 'ASC';
                    $sortBy = $by;
                    break;
            }
            $orderStatement = "{$sortBy} {$innerType}";
            return $orderStatement;
        }
    }
}
