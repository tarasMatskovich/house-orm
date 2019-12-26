<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 17.12.2019
 * Time: 11:33
 */

namespace houseorm\mapper\collection;


use Traversable;

/**
 * Class DomainCollection
 * @package houseorm\mapper\collection
 */
class DomainCollection implements DomainCollectionInterface
{

    /**
     * @var array
     */
    private $registry = [];

    /**
     * DomainCollection constructor.
     * @param array $registry
     */
    public function __construct(array $registry = [])
    {
        $this->registry = $registry;
    }

    /**
     * @param $value
     * @param null $key
     */
    public function add($value, $key = null)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return $this->registry[$key] ?? $default;
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->registry);
    }

    /**
     * @return array
     */
    public function toArray()
    {
       return $this->registry;
    }

    /**
     * @return mixed|null
     */
    public function first()
    {
        return $this->get(array_key_first($this->registry));
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->registry);
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->registry[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->registry[$offset] ?? null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->registry[] = $value;
        } else {
            $this->registry[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->registry[$offset]);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->registry);
    }
}
