<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 12:29
 */

namespace houseorm\gateway\datatable\query\traits;

/**
 * Class BindingsEnum
 * @package houseorm\gateway\datatable\query\traits
 */
class BindingsEnum
{

    const CRITERIA_BINDING = ':';

    const ORDER_BINDING = ':order';

    const LIMIT_BINDING = ':limit';

    const OFFSET_BINDING = ':offset';

}
