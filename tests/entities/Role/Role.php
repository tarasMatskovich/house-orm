<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 21.12.2019
 * Time: 15:49
 */

namespace tests\entities\Role;

use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;

/**
 * Class Role
 * @package tests\entities\Role
 * @Gateway(type="datatable.roles")
 */
class Role implements RoleInterface
{

    /**
     * @var int
     * @Field(map="id")
     */
    private $id;

    /**
     * @var string
     * @Field(map="title")
     */
    private $title;

    public function __construct($title = null)
    {
        $this->title = $title;
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
    public function getTitle()
    {
        return $this->title;
    }
}
