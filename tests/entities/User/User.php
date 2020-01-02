<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:00
 */

namespace tests\entities\User;


use houseorm\Cache\CacheableEntityInterface;
use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;
use houseorm\mapper\annotations\Relation;
use houseorm\mapper\annotations\ViaRelation;

/**
 * Class User
 * @package tests\entities\User
 * @Gateway(type="datatable.users")
 * @ViaRelation(entity="Role", via="RoleUser", firstLocalKey="id", firstForeignKey="userId", secondLocalKey="id", secondForeignKey="roleId")
 * @ViaRelation(entity="Permission", via="PermissionUser", firstLocalKey="id", firstForeignKey="userId", secondLocalKey="id", secondForeignKey="permissionId")
 */
class User implements UserInterface, CacheableEntityInterface
{

    /**
     * @var int
     * @Field(map="id")
     * @Relation(entity="Comment", key="userId")
     */
    private $id;

    /**
     * @var null|string
     * @Field(map="name")
     */
    private $name;

    /**
     * User constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
