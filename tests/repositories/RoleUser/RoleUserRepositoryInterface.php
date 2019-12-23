<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 21.12.2019
 * Time: 15:52
 */

namespace tests\repositories\RoleUser;


use houseorm\mapper\DomainMapperInterface;
use tests\entities\RoleUser\RoleUserInterface;

/**
 * Interface RoleUserRepositoryInterface
 * @package tests\repositories\RoleUser
 */
interface RoleUserRepositoryInterface extends DomainMapperInterface
{

    /**
     * @param $id
     * @return RoleUserInterface|null
     */
    public function find($id);

}
