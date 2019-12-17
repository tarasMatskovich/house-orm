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
use tests\entities\Comment\Comment;
use tests\entities\User\User;
use tests\entities\User\UserInterface;
use tests\repositories\CommentRepository\CommentRepository;
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

    /**
     * @return void
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\config\ConfigException
     * @throws \houseorm\mapper\DomainMapperException
     * @throws \houseorm\EntityManagerException
     */
    public function testFindOneBy()
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
        /**
         * @var UserRepositoryInterface $userRepository
         */
        $userRepository = $entityManager->getMapper('User');
        $users = $userRepository->findBy([]);
    }

    /**
     * @return void
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\mapper\DomainMapperException
     */
    public function testFindByInMemory()
    {
        /**
         * @var $userRepository UserRepositoryInterface
         */
        $userRepository = new UserRepository(
            User::class,
            new DataTableGateway(new InMemoryConnection())
        );
        $user1 = new User('taras');
        $user2 = new User('taras');
        $user3 = new User('bohdan');
        $userRepository->save($user1);
        $userRepository->save($user2);
        $userRepository->save($user3);
        $user = $userRepository->findBy(['name' => 'taras2']);
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\mapper\DomainMapperException
     * @throws \houseorm\config\ConfigException
     * @throws \houseorm\EntityManagerException
     */
    public function testRelations()
    {
        $entityManager = new EntityManager();
        $config = new Config([
            'driver' => Config::DRIVER_MEMORY
        ]);
        $entityManager->setDefaultConfig($config);
        $connection = new InMemoryConnection();
        $userRepository = new UserRepository(
            User::class,
            new DataTableGateway($connection)
        );
        $entityManager->setMapper('User', $userRepository);
        $commentRepository = new CommentRepository(
            Comment::class,
            new DataTableGateway($connection)
        );
        $entityManager->setMapper('Comment', $commentRepository);
        $userRepository = $entityManager->getMapper('User');
        $commentRepository = $entityManager->getMapper('Comment');
        $user1 = new User('Taras');
        $user2 = new User('Bohdan');
        $userRepository->save($user1);
        $userRepository->save($user2);
        $comment1 = new Comment('taras comment', $user1->getId());
        $comment11 = new Comment('taras comment 2', $user1->getId());
        $comment2 = new Comment('bohdan coment', $user2->getId());
        $commentRepository->save($comment1);
        $commentRepository->save($comment11);
        $commentRepository->save($comment2);
        $user1Comments = $userRepository->findRelative($user1, 'Comment');
        $user2Comments = $userRepository->findRelative($user2, 'Comment');
        /**
         * @var UserInterface $commentUser
         */
        $commentUser = $commentRepository->findRelativeOne($comment1, 'User');
        $commentUser->setName('Taras 2');
        $commentRepository->saveRelative($commentUser, 'User');
        $commentUser = $commentRepository->findRelativeOne($comment1, 'User');
    }

}
