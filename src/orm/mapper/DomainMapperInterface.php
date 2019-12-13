<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 15:59
 */

namespace houseorm\mapper;

use houseorm\mapper\object\DomainObjectInterface;

/**
 * Class DomainMapperInterface
 * @package houseorm\mapper
 */
interface DomainMapperInterface
{

    /**
     * @param $id
     * @return DomainObjectInterface|null
     */
    public function find($id);

    /**
     * @param array $criteria
     * @return DomainObjectInterface[]
     */
    public function findBy($criteria);

    /**
     * @param array $criteria
     * @return DomainObjectInterface
     */
    public function findOneBy($criteria);

    /**
     * @param $entity
     * @return void
     */
    public function save(&$entity);

    /**
     * @param $entity
     * @return void
     */
    public function delete($entity);

}
