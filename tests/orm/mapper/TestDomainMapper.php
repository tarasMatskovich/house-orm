<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:13
 */

namespace tests\orm\mapper;

use houseorm\gateway\connection\InMemoryConnection;
use houseorm\gateway\datatable\DataTableGateway;
use tests\entities\User\User;
use tests\repositories\UserRepository\UserRepository;
use tests\repositories\UserRepository\UserRepositoryInterface;

/**
 * Class TestDomainMapper
 * @package tests\orm\mapper
 */
class TestDomainMapper extends \PHPUnit_Framework_TestCase
{

    public function testUserRepository()
    {
        /**
         * @var $userRepository UserRepositoryInterface
         */
        $userRepository = new UserRepository(
            User::class,
            new DataTableGateway(new InMemoryConnection())
        );
        $user = new User('Тарас');
        $userRepository->save($user);
        $newUser = $userRepository->find($user->getId());
        $user->setName('A');
        $newUser->setName('B');
        $userRepository->save($newUser);
        $newUserAfterUpdate = $userRepository->find($newUser->getId());
        $userRepository->delete($newUserAfterUpdate);
        $newUserAfterDelete = $userRepository->find($newUserAfterUpdate->getId());
    }

}
