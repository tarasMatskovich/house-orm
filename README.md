# House ORM

House ORM is a simply PHP object relationship mapping library for wotk with data in database.

# Available drivers

  - Memory (for test environment)
  - MySQL

# Installing

```sh
composer require tarasmatskovich/house-orm
```

# Usage 

For example in datatabase exists table users with fields id, name. You have to create corresponding entity Class and specify fields mapping:

```php
<?php

use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;

/**
 * Class User
 * @package house\Entities\User
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
```

Important part of entity class is docs annotations. It uses for mapping specifing
First required type of entity annotations is Gateway in format. 

```php
* @Gateway(type="datatable.tablename")
```
For example if you have table in database named users you have to write:
```php
* @Gateway(type="datatable.users")
```

Second type of entity annotations is Field
```php
* @Field(map="column_name")
```
You should specify this annotation on entity class field which want to map:
```php
`   /**
     * @Field(map="name")
     */
    private $name;
```

Then you have to create repository (mapper) class for work with corresponding entity wich extends houseorm/mapper/DomainMapper class.
Lets continue exmple with users:
```php
<?php

namespace house\Repositories\UserRepository;

use houseorm\mapper\DomainMapper;

/**
 * Class UserRepository
 * @package house\Repositories
 */
class UserRepository extends DomainMapper implements UserRepositoryInterface
{

}
```

Then you should to register this repository (mapper) in entity manager.
This is config params:

```php

use houseorm\config\Config;

 $configParams = [
        'driver' => Config::DRIVER_MYSQL,
        'host'=> '127.0.0.1',
        'database' => 'orm',
        'user' => 'root',
        'password' => ''
];
```
House ORM have two drivers. First for real with real mysql database:
```php
Config::DRIVER_MYSQL
```
Second for tests (All data will be save on PHP proccess memory and you do not need real database):
```php
Config::DRIVER_MEMORY
```
Creating entity manager and register UserRepository and User entity:

```php

use houseorm\config\Config;
use houseorm\EntityManager;
use house\Repositories\UserRepository;

 $configParams = [
        'driver' => Config::DRIVER_MYSQL,
        'host'=> '127.0.0.1',
        'database' => 'orm',
        'user' => 'root',
        'password' => ''
];

$config = new Config($configParams);
$houseEntityManager = new EntityManager($config);
$houseEntityManager->setMapper('User', new UserRepository(User::class));
```

Then you can get access to repository:
```php
$userRepository = $entityManager->getMapper('User');
```

# Find
To find user entity with primary key (default `id`):
```php
$user = $userRepository->find(5);
if (null !== $user) {
    $user->getId(); // 5
    $user->getName(); // my name
}
```
Also you can find entity through criteria
```php
$user = $userRepository->findOneBy(['name' => 'Test name');
...
$users = $userRepository->findBy([['name', 'LIKE', '%name%']);
foreach ($users as $user) {
    //
}
```

# Save
To save new entity you have to create new object of entity class, fill fields and save through repository:
```php
$user = new User();
$user->setName('Test name');
$userRepository->save($user);
$user->getId() // 6
$user->getName() // Test name
```

# Update 
To update entity you may change some fields and save entity object through repository:
```php
$user->setName('Updated name');
$userRepository->save($user);
...
$user = $userRepository->find(6);
$user->getName(); // Updated name
```
# Delete
To delete entity:
```php
$user = $userRepository->find(6);
if (null !== $user) {
    $userRepository->delete($user);
}
```

# Relations
For specifying relations you have to use special annotations. Exist two types of relations:
- Simple relation (for example table `comments` have field `user_id` which directly related with table `users` and field `id`)
- Complicated relation wich need one more binding table (for exampe exist tables `users`, `roles`, and binding table `user_roles` which have binding fields `user_id` and `role_id` which related with fields `id` in `users` and `roles` tables corresponding)

# Simple relations
Specifying by special annotation `Relation`. This is our example with users. User entity class:
```php
use houseorm\mapper\annotations\Relation;

/**
 * Class User
 * @package house\Entities\User
 * @Gateway(type="datatable.users")
 */
class User implements UserInterface {

    /**
     * @var int
     * @Field(map="id")
     * @Relation(entity="Comment", key="userId")
     */
    private $id;
    ...
}
```

```php
* @Relation(entity="Entity key in entity manager", key="related field in this entity")
```

And new entity Comment class:
```php
<?php

use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;
use houseorm\mapper\annotations\Relation;


/**
 * Class Comment
 * @package house\Entities\Comment
 * @Gateway(type="datatable.comments")
 */
class Comment implements CommentInterface
{
    ...
    /**
     * @var null|int
     * @Field(map="user_id")
     * @Relation(entity="User", key="id")
     */
    private $userId;
    ...
}

```

# Complicated relations
To specify complicated relations you have to use special annotation `ViaRelation`
Example - entity `Role` related with `User` through binding entity `UserRole`
User entity class:
```php
use houseorm\mapper\annotations\ViaRelation;

/**
 * Class User
 * @package house\Entities\User
 * @Gateway(type="datatable.users")
 * @ViaRelation(entity="Role", via="UserRole", firstLocalKey="id", firstForeignKey="userId", secondLocalKey="id", secondForeignKey="roleId")
 */
class User implements UserInterface
```
UserRole entity:
```php
<?php

namespace house\Entities\UserRole;

use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;
use houseorm\mapper\annotations\Relation;

/**
 * Class UserRole
 * @package house\Entities\UserRole
 * @Gateway(type="datatable.user_roles")
 */
class UserRole implements UserRoleInterface
{

    /**
     * @var int
     * @Field(map="id")
     */
    private $id;

    /**
     * @var int
     * @Field(map="user_id")
     * @Relation(entity="User", key="id")
     */
    private $userId;

    /**
     * @var int
     * @Field(map="role_id")
     * @Relation(entity="Role", key="id")
     */
    private $roleId;
    ...

}

```

Role entity class:
```php
<?php

namespace house\Entities\Role;

use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Field;

/**
 * Class Role
 * @package house\Entities\Role
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
    ...
}

```

# Cache 
You can use cache to increase performance of ORM work. House ORM support two drivers of cache:
- Memory cache (usefull for async php)
- Redis cache
Usage memory cache:
```php
use houseorm\Cache\Config\CacheConfig;
use houseorm\config\Config;
use houseorm\EntityManager;

$config = new Config($configParams);
$config->setCacheConfig(new CacheConfig(CacheConfig::MEMORY_DRIVER));
$entityManager = new EntityManager($config);
```
Usage Redis cache:
```php
use houseorm\Cache\Config\CacheConfig;
use houseorm\config\Config;
use houseorm\EntityManager;

$config = new Config($configParams);
// driver - CacheConfig::REDIS_DRIVER
// cache lifetime - 10
// Redis host - 127.0.0.1
$config->setCacheConfig(new CacheConfig(CacheConfig::REDIS_DRIVER, 10, '127.0.0.1'));
$entityManager = new EntityManager($config);
```
