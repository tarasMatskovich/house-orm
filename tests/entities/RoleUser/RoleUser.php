<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 21.12.2019
 * Time: 15:37
 */

namespace tests\entities\RoleUser;

use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;

/**
 * Class RoleUser
 * @package tests\entities\RoleUser
 * @Gateway(type="datatable.user_roles")
 */
class RoleUser implements RoleUserInterface
{

    /**
     * @var int
     * @Field(map="id")
     */
    private $id;

    /**
     * @var int
     * @Field(map="user_id")
     */
    private $userId;

    /**
     * @var int
     * @Field(map="role_id")
     */
    private $roleId;

    /**
     * RoleUser constructor.
     * @param null $userId
     * @param null $roleId
     */
    public function __construct($userId = null, $roleId = null)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }
}
