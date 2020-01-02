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
     * @var mixed|null
     */
    private $pk;

    /**
     * FindCacheRequest constructor.
     * @param $target
     * @param $pk
     */
    public function __construct($target, $pk)
    {
        $this->target = $target;
        $this->pk = $pk;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return mixed|null
     */
    public function getPrimaryKey()
    {
        return $this->pk;
    }
}
