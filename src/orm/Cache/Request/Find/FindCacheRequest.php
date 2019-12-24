<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 18:26
 */

namespace houseorm\Cache\Request\Find;

/**
 * Class CacheRequest
 * @package houseorm\Cache\Request
 */
class FindCacheRequest implements FindCacheRequestInterface
{

    /**
     * @var string
     */
    private $target;

    /**
     * @var array
     */
    private $criteria;

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }
}
