<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:00
 */

namespace tests\entities\User;


use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;

/**
 * Class User
 * @package tests\entities\User
 * @Gateway(type="datatable.users")
 */
class User implements UserInterface
{

    /**
     * @var int
     * @Field(map="id")
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
}
