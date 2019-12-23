<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 15:59
 */

namespace houseorm\mapper;

use houseorm\EntityManagerInterface;
use houseorm\mapper\collection\DomainCollectionInterface;
use houseorm\mapper\object\DomainObjectInterface;

/**
 * Class DomainMapperInterface
 * @package houseorm\mapper
 */
interface DomainMapperInterface
{

    /**
     * @return array
     */
    public function getMapping();

    /**
     * @param $id
     * @return DomainObjectInterface|null
     */
    public function find($id);

    /**
     * @param array $criteria
     * @return DomainCollectionInterface
     */
    public function findBy($criteria);

    /**
     * @param array $criteria
     * @return DomainObjectInterface|null
     */
    public function findOneBy($criteria);

    /**
     * @param $entity
     * @param $relativeEntityName
     * @return DomainCollectionInterface
     */
    public function findRelative($entity, $relativeEntityName);

    /**
     * @param $entity
     * @param $relativeEntityName
     * @return DomainObjectInterface|null
     */
    public function findRelativeOne($entity, $relativeEntityName);

    /**
     * @param $entity
     * @param $relativeEntityName
     * @param $criteria
     * @return DomainCollectionInterface
     */
    public function findRelativeBy($entity, $relativeEntityName, $criteria);

    /**
     * @param $entity
     * @param $relativeEntityName
     * @param $criteria
     * @return DomainObjectInterface|null
     */
    public function findRelativeOneBy($entity, $relativeEntityName, $criteria);

    /**
     * @param $entity
     * @return void
     */
    public function save(&$entity);

    /**
     * @param $entity
     * @param $relativeEntityName
     * @return void
     */
    public function saveRelative(&$entity, $relativeEntityName);

    /**
     * @param $entity
     * @return void
     */
    public function delete($entity);

    /**
     * @param EntityManagerInterface $em
     * @return void
     */
    public function setEntityManager(EntityManagerInterface $em);

    /**
     * @return string
     */
    public function getEntity();



}
