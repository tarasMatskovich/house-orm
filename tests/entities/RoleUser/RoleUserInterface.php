<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 21.12.2019
 * Time: 15:54
 */

namespace tests\entities\RoleUser;

/**
 * Interface RoleUserInterface
 * @package tests\entities\RoleUser
 */
interface RoleUserInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getUserId();

    /**
     * @return int
     */
    public function getRoleId();

}
