<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 15:57
 */

namespace houseorm;

use houseorm\mapper\DomainMapperInterface;

/**
 * Interface EntityManagerInterface
 * @package houseorm
 */
interface EntityManagerInterface
{

    /**
     * @param string $mapper
     * @return DomainMapperInterface|null
     */
    public function getMapper(string $mapper);

    /**
     * @param string $key
     * @param DomainMapperInterface $mapper
     * @return void
     */
    public function setMapper(string $key, DomainMapperInterface $mapper);

}
