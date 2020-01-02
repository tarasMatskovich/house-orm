<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 18:09
 */

namespace houseorm\Cache\Drivers\MemoryDriver\Registry;

use houseorm\mapper\collection\DomainCollectionInterface;

/**
 * Interface RegistryInterface
 * @package houseorm\Cache\Drivers\MemoryDriver\Registry
 */
interface RegistryInterface
{

    /**
     * @param $target
     * @param $pk
     * @return mixed|null
     */
    public function getItemByPrimaryKey($target, $pk);

    /**
     * @param $target
     * @param $pk
     * @param $entity
     * @return void
     */
    public function setItem($target, $pk, $entity);

    /**
     * @param $target
     * @param $pk
     * @return void
     */
    public function resetItem($target, $pk);

}
