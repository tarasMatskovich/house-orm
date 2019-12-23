<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 21.12.2019
 * Time: 16:40
 */

namespace tests\entities\Role;

/**
 * Interface RoleInterface
 * @package tests\entities\Role
 */
interface RoleInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

}
