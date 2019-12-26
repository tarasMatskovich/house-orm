<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 11.12.2019
 * Time: 11:13
 */

namespace tests\orm\mapper;

use houseorm\Cache\Cache;
use houseorm\Cache\Config\CacheConfig;
use houseorm\config\Config;
use houseorm\config\ConfigInterface;
use houseorm\EntityManager;
use houseorm\EntityManagerInterface;
use tests\entities\Comment\Comment;
use tests\entities\Role\Role;
use tests\entities\RoleUser\RoleUser;
use tests\entities\User\User;
use tests\repositories\CommentRepository\CommentRepository;
use tests\repositories\CommentRepository\CommentRepositoryInterface;
use tests\repositories\Role\RoleRepository;
use tests\repositories\Role\RoleRepositoryInterface;
use tests\repositories\RoleUser\RoleUserRepository;
use tests\repositories\RoleUser\RoleUserRepositoryInterface;
use tests\repositories\UserRepository\UserRepository;
use tests\repositories\UserRepository\UserRepositoryInterface;

/**
 * Class TestDomainMapper
 * @package tests\orm\mapper
 */
class TestDomainMapper extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    private $databaseConfig = [
        'driver' => Config::DRIVER_MYSQL,
        'host'=> '127.0.0.1',
        'database' => 'orm',
        'user' => 'root',
        'password' => ''
    ];

    /**
     * @var array
     */
    private $memoryConfig = [
        'driver' => Config::DRIVER_MEMORY
    ];

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\config\ConfigException
     * @throws \houseorm\mapper\DomainMapperException
     */
    public function testMemoryMapper()
    {
        $config = new Config($this->memoryConfig);
        $entityManager = $this->makeEntityManager($config);
        $this->testMapper($entityManager);
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\config\ConfigException
     * @throws \houseorm\mapper\DomainMapperException
     */
    public function testDatabaseMapper()
    {
        $config = new Config($this->databaseConfig);
        $entityManager = $this->makeEntityManager($config);
        $this->testMapper($entityManager);
    }

    /**
     * @param ConfigInterface $config
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\mapper\DomainMapperException
     * @return EntityManagerInterface
     */
    private function makeEntityManager(ConfigInterface $config)
    {
        $entityManager = new EntityManager($config);
        $userRepository = new UserRepository(User::class);
        $commentRepository = new CommentRepository(Comment::class);
        $roleRepository = new RoleRepository(Role::class);
        $roleUserRepository = new RoleUserRepository(RoleUser::class);
        $entityManager->setMapper('User', $userRepository);
        $entityManager->setMapper('Comment', $commentRepository);
        $entityManager->setMapper('Role', $roleRepository);
        $entityManager->setMapper('RoleUser', $roleUserRepository);
        return $entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    protected function testMapper(EntityManagerInterface $entityManager)
    {
        /**
         * @var UserRepositoryInterface $userRepository
         */
        $userRepository = $entityManager->getMapper('User');
        /**
         * @var CommentRepositoryInterface $commentRepository
         */
        $commentRepository = $entityManager->getMapper('Comment');
        /**
         * @var RoleRepositoryInterface $roleRepository
         */
        $roleRepository = $entityManager->getMapper('Role');
        /**
         * @var RoleUserRepositoryInterface $roleUserRepository
         */
        $roleUserRepository = $entityManager->getMapper('RoleUser');
        $user1 = new User('Taras');
        $user2 = new User('Bohdan');
        $userRepository->save($user1);
        $userRepository->save($user2);
        $this->assertNotNull($user1->getId());
        $this->assertNotNull($user2->getId());
        $user1New = $userRepository->find($user1->getId());
        $this->assertEquals($user1->getId(), $user1->getId());
        $this->assertEquals($user1->getName(), $user1New->getName());
        /**
         * testing relations
         */
        $user1Comment1 = new Comment('Taras comment 1', $user1->getId());
        $user1Comment2 = new Comment('Taras comment 2', $user1->getId());
        $user2Comment1 = new Comment('Bodan comment 1', $user2->getId());
        $commentRepository->save($user1Comment1);
        $commentRepository->save($user1Comment2);
        $commentRepository->save($user2Comment1);
        $userRepository->saveRelative($user1Comment2, 'User');
        $user1Comments = $userRepository->findRelative($user1, 'Comment');
        $this->assertCount(2, $user1Comments);
        $user1Comments = $userRepository->findRelativeBy($user1, 'Comment', ['content' => 'Taras comment 1']);
        $this->assertCount(1, $user1Comments);
        $user1Comments = $userRepository->findRelativeBy($user1, 'Comment', ['content' => 'Non existing content']);
        $this->assertCount(0, $user1Comments);
        /**
         * testing a via relations
         */
        $role1 = new Role('Admin');
        $roleRepository->save($role1);
        $role2 = new Role('Editor');
        $roleRepository->save($role2);
        $userRole = new RoleUser($user1->getId(), $role1->getId());
        $roleUserRepository->save($userRole);
        $userRole = new RoleUser($user1->getId(), $role2->getId());
        $roleUserRepository->save($userRole);
        $userRole = new RoleUser($user2->getId(), $role1->getId());
        $roleUserRepository->save($userRole);
        $user1Roles = $userRepository->findRelative($user1, 'Role');
        $this->assertCount(2, $user1Roles);
        $user2Roles = $userRepository->findRelative($user2, 'Role');
        $this->assertCount(1, $user2Roles);
        $user1Roles = $userRepository->findRelativeBy($user1, 'Role', ['title' => 'Editor']);
        $this->assertCount(1, $user1Roles);
        $user2Role = $userRepository->findRelativeOneBy($user2, 'Role', ['title' => 'Editor']);
        $this->assertNull($user2Role);
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \houseorm\config\ConfigException
     * @throws \houseorm\mapper\DomainMapperException
     */
    public function testCachedDomainMapper()
    {
        $config = new Config($this->memoryConfig);
        $config->setCacheConfig(new CacheConfig(CacheConfig::MEMORY_DRIVER));
        $entityManager = new EntityManager($config);
        $userRepository = new UserRepository(User::class);
        $entityManager->setMapper('User', $userRepository);
        /**
         * @var UserRepositoryInterface $userRepository
         */
        $userRepository = $entityManager->getMapper('User');
        $user = new User('Taras');
        $userRepository->save($user);
        $foundedUser = $userRepository->find($user->getId());
        $cachedUser = $userRepository->find($user->getId());
        $this->assertEquals($foundedUser->getName(), $cachedUser->getName());
        $foundedUser->setName('Bohdan');
        $this->assertNotEquals($foundedUser->getName(), $cachedUser->getName());
    }

}
