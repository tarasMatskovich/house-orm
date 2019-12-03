<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:04
 */

namespace houseorm;


use houseorm\mapper\DomainMapperInterface;

/**
 * Class EntityManager
 * @package houseorm
 */
class EntityManager implements EntityManagerInterface
{

    /**
     * @var DomainMapperInterface[]
     */
    private $mappers;

    /**
     * @param string $mapper
     * @return void
     * @throws EntityManagerException
     */
    public function getMapper(string $mapper)
    {
        if (!isset($this->mappers[$mapper])) {
            throw new EntityManagerException("Mapper {$mapper}  was not found");
        }
    }

    /**
     * @param string $key
     * @param DomainMapperInterface $mapper
     * @return void
     */
    public function setMapper(string $key, DomainMapperInterface $mapper)
    {
        $this->mappers[$key] = $mapper;
    }
}
