<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:13
 */

namespace tests\orm\mapper;

use tests\entities\User\User;
use tests\repositories\UserRepository\UserRepository;

/**
 * Class TestDomainMapper
 * @package tests\orm\mapper
 */
class TestDomainMapper extends \PHPUnit_Framework_TestCase
{

    public function testUserRepository()
    {
        $userRepository = new UserRepository(
            User::class
        );
        $res = $userRepository->find(10);
    }

}
