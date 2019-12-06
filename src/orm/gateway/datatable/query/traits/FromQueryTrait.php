<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 06.12.2019
 * Time: 17:52
 */

namespace houseorm\gateway\datatable\query\traits;

/**
 * Trait FromQueryTrait
 */
trait FromQueryTrait
{

    /**
     * @var array
     */
    protected $from;

    /**
     * @return string
     */
    protected function getFrom()
    {
        $from = $this->from;
        return (!$from) ? '' : $from[0] ?? '';
    }

}
