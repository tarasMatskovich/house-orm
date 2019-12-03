<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 02.12.2019
 * Time: 16:12
 */

namespace houseorm\mapper\object;

/**
 * Interface DomainObjectInterface
 * @package houseorm\mapper\object
 */
interface DomainObjectInterface
{

    /**
     * @return array
     */
    public function getAttributes();

}
