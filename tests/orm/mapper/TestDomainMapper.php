<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:13
 */

namespace tests\orm\mapper;

use houseorm\config\Config;
use houseorm\EntityManager;
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

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\mapper\DomainMapperException
     */
    public function testUserRepositoryInMemory()
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

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\config\ConfigException
     * @throws \houseorm\mapper\DomainMapperException
     * @throws \houseorm\EntityManagerException
     */
    public function testMapperWithDatabase()
    {
        $entityManager = new EntityManager();
        $config = new Config([
            'driver' => Config::DRIVER_MYSQL,
            'host'=> '127.0.0.1',
            'database' => 'orm',
            'user' => 'root',
            'password' => ''
        ]);
        $entityManager->setDefaultConfig($config);
        $entityManager->setMapper('User', new UserRepository(User::class));
        $user = new User('Test user');
        /**
         * @var UserRepositoryInterface $userRepository
         */
        $userRepository = $entityManager->getMapper('User');
//        $userRepository->save($user);
        $user = $userRepository->find(4);
        $user->setName('Updated Test User');
        $userRepository->delete($user);
    }

}
