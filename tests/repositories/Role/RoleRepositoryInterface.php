<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 21.12.2019
 * Time: 16:45
 */

namespace tests\repositories\Role;

use houseorm\mapper\DomainMapperInterface;
use tests\entities\Role\RoleInterface;

/**
 * Interface RoleInterface
 * @package tests\repositories\Role
 */
interface RoleRepositoryInterface extends DomainMapperInterface
{

    /**
     * @param $id
     * @return RoleInterface|null
     */
    public function find($id);

}
