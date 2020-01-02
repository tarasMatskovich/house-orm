<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 17.12.2019
 * Time: 11:29
 */

namespace houseorm\mapper\collection;

/**
 * Interface DomainCollectionInterface
 * @package houseorm\mapper\collection
 */
interface DomainCollectionInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{

    /**
     * @param $value
     * @param null $key
     * @return void
     */
    public function add($value, $key = null);

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param $key
     * @return void
     */
    public function remove($key);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return mixed|null
     */
    public function first();

}
