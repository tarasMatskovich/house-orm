<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 24.12.2019
 * Time: 12:52
 */

namespace tests\EventManager;

use houseorm\config\Config;
use houseorm\EntityManager;
use houseorm\EventManager\EventManager;
use houseorm\Logger\Logger;
use tests\entities\User\User;
use tests\repositories\UserRepository\UserRepository;

/**
 * Class TestEventManager
 * @package tests\EventManager
 */
class TestEventManager extends \PHPUnit_Framework_TestCase
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
    public function testEventManager()
    {
        $config = new Config($this->memoryConfig);
        $logger = new Logger();
        $eventManager = new EventManager($logger);
        $entityManager = new EntityManager($config, $eventManager);
        $userRepository = new UserRepository(User::class);
        $entityManager->setMapper('User', $userRepository);
        $userRepository = $entityManager->getMapper('User');
        $user = new User('Taras');
        $userRepository->save($user);
        $user->setName('Taras 2');
        $userRepository->save($user);
        $user = $userRepository->find($user->getId());
        $userRepository->delete($user);
    }

}
