<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 18:09
 */

namespace houseorm\Cache\Drivers\MemoryDriver\Registry;

use houseorm\Cache\Config\CacheConfig;

/**
 * Class Registry
 * @package houseorm\Cache\Drivers\MemoryDriver\Registry
 */
class Registry implements RegistryInterface
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var int
     */
    private $lifetime;

    /**
     * Registry constructor.
     * @param int $lifetime
     */
    public function __construct(int $lifetime = CacheConfig::DEFAULT_LIFETIME)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * @param $target
     * @param $pk
     * @return mixed|null
     */
    public function getItemByPrimaryKey($target, $pk)
    {
        $this->hook($target, $pk);
        if ($this->isTargetEmpty($target)) {
           return null;
        }
        return $this->data[$target][$pk] ?? null;
    }

    /**
     * @param $target
     * @param $pk
     * @param $entity
     */
    public function setItem($target, $pk, $entity)
    {
        $this->hook($target, $pk);
        $this->data[$target][$pk] = [
            'entity' => clone $entity,
            'expiredAt' => time() + ($this->lifetime * 60)
        ];
    }

    public function resetItem($target, $pk)
    {
        if (!$this->isTargetEmpty($target)) {
            unset($this->data[$target][$pk]);
        }
    }

    /**
     * @param string $target
     * @return bool
     */
    private function isTargetEmpty(string $target)
    {
        return !isset($this->data[$target]) || (isset($this->data[$target]) && empty($this->data[$target]));
    }

    /**
     * @return void
     */
    private function resetRegistry()
    {
        $this->data = [];
    }

    /**
     * @param $target
     * @param $pk
     */
    private function hook($target, $pk)
    {
        if (!$this->isTargetEmpty($target) && isset($this->data[$target][$pk]) && isset($this->data[$target][$pk]['expiredAt']) && time() > (int)$this->data[$target][$pk]['expiredAt']) {
            $this->resetItem($target, $pk);
        }
    }
}
