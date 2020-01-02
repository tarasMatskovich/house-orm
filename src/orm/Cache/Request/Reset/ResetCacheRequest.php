<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 26.12.2019
 * Time: 18:04
 */

namespace houseorm\Cache\Request\Reset;

/**
 * Class ResetCacheRequest
 * @package houseorm\Cache\Request\Reset
 */
class ResetCacheRequest implements ResetCacheRequestInterface
{

    /**
     * @var string
     */
    private $target;

    /**
     * @var mixed
     */
    private $pk;

    /**
     * ResetCacheRequest constructor.
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
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->pk;
    }
}
