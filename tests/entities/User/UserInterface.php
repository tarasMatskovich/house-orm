<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:00
 */

namespace tests\entities\User;

/**
 * Interface UserInterface
 * @package tests\entities\User
 */
interface UserInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param $name
     * @return void
     */
    public function setName($name);

}
